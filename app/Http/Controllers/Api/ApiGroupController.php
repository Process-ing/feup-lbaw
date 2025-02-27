<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupInvitation;
use App\Models\GroupJoinRequest;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiGroupController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $visibleGroups = Group::visibleTo($user)
            ->select(['id', 'name', 'owner_id', 'description', 'creation_timestamp', 'is_public', 'member_count'])
            ->get();

        $nonVisibleGroups = Group::whereNotIn('id', $visibleGroups->pluck('id'))
            ->select(['id', 'name', 'description', 'is_public'])
            ->get();
        $groups = $visibleGroups->merge($nonVisibleGroups);

        return response()->json($groups);
    }

    public function show(Request $request, int $group_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('view', $group);

        $groupData = $group->only(['id', 'name', 'owner_id', 'description', 'creation_timestamp', 'is_public', 'member_count']);

        if (! auth()->user()->can('viewContent', $group)) {
            unset($groupData['owner_id']);
            unset($groupData['creation_timestamp']);
            unset($groupData['member_count']);
        }

        return response()->json($groupData);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Group::class);

        $request->validate([
            'name' => 'required|string|unique:groups,name',
            'description' => 'required|string',
            'is_public' => 'required|boolean',
        ]);

        $group = new Group;
        $group->name = $request->input('name');
        $group->description = $request->input('description');
        $group->is_public = $request->input('is_public') ?? true;
        $group->owner_id = Auth::id();
        $group->save();

        return response()->json($group, 201);
    }

    public function update(Request $request, int $id)
    {
        $group = Group::findOrFail($id);

        $this->authorize('update', $group);

        $request->validate([
            'name' => 'required|string|unique:groups,name,'.$id,
            'description' => 'required|string',
            'is_public' => 'required|boolean',
        ]);

        if ($group->is_public != $request->input('is_public')) {
            foreach ($group->posts as $post) {
                $post->is_public = $request->input('is_public');
                $post->save();
            }
        }

        if ($request->input('is_public')) {
            GroupJoinRequest::where('group_id', $id)->where('status', 'pending')->update(['status' => 'accepted']);
        }

        $group->name = $request->input('name');
        $group->description = $request->input('description');
        $group->is_public = $request->input('is_public');
        $group->owner_id = auth()->id();
        $group->save();

        return response()->json($group);
    }

    public function join(Request $request, int $group_id)
    {
        $group = Group::findOrFail($group_id);

        $this->authorize('join', $group);

        if ($group->is_public) {
            $groupMember = new GroupMember;
            $groupMember->group_id = $group_id;
            $groupMember->user_id = auth()->id();
            $groupMember->join_timestamp = now();
            $groupMember->save();
        } else {
            $groupJoinRequest = new GroupJoinRequest;
            $groupJoinRequest->status = 'pending';
            $groupJoinRequest->group_id = $group_id;
            $groupJoinRequest->requester_id = auth()->id();
            $groupJoinRequest->save();
        }

        return response()->json(['message' => 'You have joined the group.']);
    }

    public function leave(Request $request, int $group_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('leave', $group);

        GroupMember::where('group_id', $group_id)->where('user_id', auth()->id())->delete();

        return response()->json(['message' => 'You have left the group.']);
    }

    public function remove(Request $request, int $group_id, int $user_id)
    {
        $group = Group::findOrFail($group_id);
        $member = User::findOrFail($user_id);

        $this->authorize('remove', [$group, $member]);

        GroupMember::where('group_id', $group_id)->where('user_id', $user_id)->delete();

        return response()->json(['message' => 'User removed from group.']);
    }

    public function acceptRequest(Request $request, int $group_id, int $requester_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('manageRequests', $group);

        $groupJoinRequest = GroupJoinRequest::where('group_id', $group_id)->where('requester_id', $requester_id)->where('status', 'pending')->firstOrFail();
        $groupJoinRequest->status = 'accepted';
        $groupJoinRequest->save();

        // Accepting a join request also removes any pending invitations
        GroupInvitation::where('group_id', $group_id)->where('invitee_id', $requester_id)->where('status', 'pending')->delete();

        return response()->json(['message' => 'Request accepted.']);
    }

    public function rejectRequest(Request $request, int $group_id, int $requester_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('manageRequests', $group);

        $groupJoinRequest = GroupJoinRequest::where('group_id', $group_id)->where('requester_id', $requester_id)->where('status', 'pending')->firstOrFail();
        $groupJoinRequest->status = 'rejected';
        $groupJoinRequest->save();

        return response()->json(['message' => 'Request rejected.']);
    }

    public function invite(Request $request, int $group_id, int $invitee_id)
    {
        $group = Group::findOrFail($group_id);
        $invitee = User::findOrFail($invitee_id);
        $this->authorize('invite', [$group, $invitee]);

        $invitation = new GroupInvitation;

        $invitation->group_id = $group_id;
        $invitation->invitee_id = $invitee_id;

        $invitation->save();

        return response()->json(['message' => 'User invited to group.']);
    }

    public function uninvite(Request $request, int $group_id, int $invitee_id)
    {
        $group = Group::findOrFail($group_id);
        $invitee = User::findOrFail($invitee_id);
        $this->authorize('invite', [$group, $invitee]);

        GroupInvitation::where('group_id', $group_id)
            ->where('invitee_id', $invitee_id)
            ->where('status', 'pending')
            ->delete();

        return response()->json(['message' => 'User uninvited from group.']);
    }

    public function acceptInvite(Request $request, int $group_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('acceptInvite', $group);

        $user = Auth::user();

        $invitations = GroupInvitation::where('group_id', $group_id)
            ->where('invitee_id', $user->id)
            ->where('status', 'pending')
            ->get();

        if ($invitations->isEmpty()) {
            return response()->json(['message' => 'No pending invitation found.'], 409);
        }

        foreach ($invitations as $invitation) {
            $invitation->update(['status' => 'accepted']);
        }

        GroupJoinRequest::where('group_id', $group_id)->where('requester_id', $user->id)->where('status', 'pending')->delete();

        return response()->json(['message' => 'You have joined the group.']);
    }

    public function rejectInvite(Request $request, int $group_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('acceptInvite', $group);

        $user = Auth::user();

        $invitations = GroupInvitation::where('group_id', $group_id)
            ->where('invitee_id', $user->id)
            ->where('status', 'pending')
            ->get();

        if ($invitations->isEmpty()) {
            return response()->json(['message' => 'No pending invitation found.'], 409);
        }

        foreach ($invitations as $invitation) {
            $invitation->update(['status' => 'rejected']);
        }

        return response()->json(['message' => 'You have rejected the invitation.']);
    }
}
