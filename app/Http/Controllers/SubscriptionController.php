<?php

namespace App\Http\Controllers;

use App\Models\Plan as ModelPlan;
use Exception;
use Illuminate\Http\Request;
use Stripe\Plan;

class SubscriptionController extends Controller
{
    public function showPlanForm()
    {
        return view('stripe.plans.create');
    }

    public function savePlan(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret')); // or env('STRIPE_SECRET')
        $amount = ($request->amount * 100);
        try {
            $plan = Plan::create([
                'amount' => $amount,
                'currency' => $request->currency, // 'usd',
                'interval' => $request->billing_period,
                'interval_count' => $request->interval_count,
                'product' => [
                    'name' => $request->name
                ]
            ]);
            // add in to dataBase
            $plans = ModelPlan:: create([
                'plan_id' => $plan->id,
                'name' => $request->name,
                'price' => $plan->amount,
                'billing_method' => $plan->interval,
                'interval_count' => $plan->interval_count,
                'currency' => $plan->currency

            ]);
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }
        return "success";
    }

    public function allPlans()
    {
        $plans = ModelPlan::get();

        return view('stripe.plans', compact('plans'));
    }

    public function show(Plan $plan, Request $request)
    {
        $intent = auth()->user()->createSetupIntent();

        return view("subscription", compact("plan", "intent"));
    }

    public function checkout($planId)
    {
        $plan = ModelPlan::where('plan_id', $planId)->first();
        if (!$plan) {
            return back()->withErrors([
                'message' => 'Unable to locate the plan'
            ]);
        }
        return view('stripe.plans.checkout', [
            'plan' => $plan,
            'intent' => auth()->user()->createSetupIntent(),
        ]);
    }

    public function processPlan(Request $request)
    {
        $user = auth()->user();
        $user->createOrGetStripeCustomer();
        $paymentMethod = null;
        $paymentMethod = $request->payment_method;
        if ($paymentMethod != null) {
            $paymentMethod = $user->addPaymentMethod($paymentMethod);
        }
        $plan = $request->plan_id;

        try {
            $user->newSubscription(
                'default', $plan
            )->create($paymentMethod != null ? $paymentMethod->id : "");
        } catch (Exception $ex) {
            return back()->withErrors([
                'error' => 'Unable to create subscription due to this issue '.$ex->getMessage()
            ]);
        }
        $request->session()->flash('alert-success', 'You are subscribed to this plan');
        return  to_route('plans.checkout', 'test');
    }
}
