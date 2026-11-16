<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $query = ContactMessage::query()->latest();

        if ($status === 'unread') {
            $query->where('is_read', false);
        } elseif ($status === 'read') {
            $query->where('is_read', true);
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $messages = $query->paginate(15)->withQueryString();

        $unreadCount = ContactMessage::where('is_read', false)->count();
        $totalCount = ContactMessage::count();

        return view('admin.contact-messages.index', compact('messages', 'unreadCount', 'totalCount', 'status'));
    }

    public function toggleRead(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->update([
            'is_read' => ! $contactMessage->is_read,
        ]);

        return back()->with('success', $contactMessage->is_read ? 'Marked as read.' : 'Marked as unread.');
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->delete();

        return back()->with('success', 'Inquiry deleted successfully.');
    }
}
