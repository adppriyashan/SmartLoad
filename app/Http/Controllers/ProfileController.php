<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function showCompleteForm()
    {
        return view('auth.profile-completion');
    }

    public function show()
    {
        return view('profile.show', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'nic' => 'required|string|max:12|unique:users,nic,' . Auth::id(),
            'address' => 'required|string',
            'tel' => 'required|string|max:20',
            'dob' => 'required|date',
            'job' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $nic = $request->nic;
        $dob = Carbon::parse($request->dob);

        // NIC Validation logic
        if (!$this->validateNIC($nic, $dob)) {
            return back()->withErrors(['nic' => 'The NIC does not match your Date of Birth or is invalid.'])->withInput();
        }

        // Age validation
        $age = $dob->age;
        if ($age < 20 || $age > 55) {
            return back()->withErrors(['dob' => 'Age must be between 20 and 55.'])->withInput();
        }

        $user = Auth::user();
        $userData = [
            'customer_name' => $request->customer_name,
            'nic' => $nic,
            'address' => $request->address,
            'tel' => $request->tel,
            'dob' => $request->dob,
            'job' => $request->job,
            'is_profile_complete' => true,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profiles', 'public');
            $userData['image'] = $path;
        }

        $user->update($userData);

        if ($request->routeIs('profile.complete')) {
            return redirect()->route('home')->with('status', 'Profile completed successfully!');
        }

        return redirect()->back()->with('status', 'Profile updated successfully!');
    }

    private function validateNIC($nic, $dob)
    {
        $nic = strtoupper($nic);
        $year = 0;
        $days = 0;

        if (strlen($nic) == 10 && (substr($nic, -1) == 'V' || substr($nic, -1) == 'X')) {
            // Old NIC
            $year = 1900 + (int) substr($nic, 0, 2);
            $days = (int) substr($nic, 2, 3);
        } elseif (strlen($nic) == 12 && is_numeric($nic)) {
            // New NIC
            $year = (int) substr($nic, 0, 4);
            $days = (int) substr($nic, 4, 3);
        } else {
            return false;
        }

        if ($days > 500)
            $days -= 500;

        // Check if year matches
        if ($year != $dob->year)
            return false;

        // Check if day of year matches
        // Note: Sri Lankan NIC uses a slightly different day counting for Leap years sometimes, 
        // but generally it matches the day of the year.
        // We'll calculate the day of the year for the given DOB.
        $dobDayOfYear = $dob->dayOfYear;

        // There is a known issue with Feb 29 in NICs. 
        // If it's a leap year and date is after Feb 28, some systems offset by 1.
        // However, most standard validations use a simple check.
        if ($days != $dobDayOfYear) {
            // Allow 1 day difference for leap year edge cases
            if (abs($days - $dobDayOfYear) > 1) {
                return false;
            }
        }

        return true;
    }
}
