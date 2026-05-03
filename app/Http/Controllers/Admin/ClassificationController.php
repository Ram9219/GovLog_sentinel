<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogClassificationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ClassificationController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
            new Middleware('verified'),
            new Middleware('permission:manage classifications'),
        ];
    }

    /**
     * Display a listing of classification rules.
     */
    public function index()
    {
        $rules = LogClassificationRule::orderBy('priority', 'desc')->get();
        return view('admin.settings.classification', compact('rules'));
    }

    /**
     * Store a newly created classification rule.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:classification_rules',
            'classification' => 'required|string|max:100',
            'severity' => 'required|in:info,warning,error,critical',
            'patterns' => 'required|array|min:1',
            'priority' => 'integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $rule = LogClassificationRule::create([
            'name' => $request->name,
            'classification' => $request->classification,
            'severity' => $request->severity,
            'patterns' => $request->patterns,
            'priority' => $request->priority ?? 0,
            'is_active' => $request->has('is_active'),
            'created_by' => auth()->user()?->id     
        ]);

        return redirect()->route('admin.classification.index')
            ->with('success', 'Classification rule created successfully!');
    }

    /**
     * Update the specified classification rule.
     */
    public function update(Request $request, LogClassificationRule $rule)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:classification_rules,name,' . $rule->id,
            'classification' => 'required|string|max:100',
            'severity' => 'required|in:info,warning,error,critical',
            'patterns' => 'required|array|min:1',
            'priority' => 'integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $rule->update([
            'name' => $request->name,
            'classification' => $request->classification,
            'severity' => $request->severity,
            'patterns' => $request->patterns,
            'priority' => $request->priority ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.classification.index')
            ->with('success', 'Classification rule updated successfully!');
    }

    /**
     * Remove the specified classification rule.
     */
    public function destroy(LogClassificationRule $rule)
    {
        $rule->delete();
        
        return redirect()->route('admin.classification.index')
            ->with('success', 'Classification rule deleted successfully!');
    }

    /**
     * Toggle the active status of a classification rule.
     */
    public function toggle(LogClassificationRule $rule)
    {
        $rule->update(['is_active' => !$rule->is_active]);
        
        $status = $rule->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('admin.classification.index')
            ->with('success', "Classification rule {$status} successfully!");
    }
}