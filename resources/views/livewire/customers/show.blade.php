<div>
    <x-header title="Customer Profile" separator class="mb-4" />

    <div class="grid grid-cols-3 space-x-2 gap-4">
        <x-card class="bg-base-200">
            <div class="text-base space-y-4">
                <div class="space-y-1">
                    <x-info.title title="Personal info" />
                    <x-info.item label="Name" :value="$customer->name" />
                    <x-info.item label="Age" :value="$customer->age" />
                    <x-info.item label="Gender" :value="$customer->gender" />
                </div>

                <div class="space-y-1">
                    <x-info.title title="Company info" />
                    <x-info.item label="Company" :value="$customer->company" />
                    <x-info.item label="Position" :value="$customer->position" />
                </div>

                <div class="space-y-1">
                    <x-info.title title="Contact info" />
                    <x-info.item label="Email" :value="$customer->email" />
                    <x-info.item label="Phone" :value="$customer->phone" />
                    <x-info.item label="Linkedin" :value="$customer->linkedin" />
                    <x-info.item label="Facebook" :value="$customer->facebook" />
                    <x-info.item label="Twitter" :value="$customer->twitter" />
                    <x-info.item label="Instagram" :value="$customer->instagram" />
                </div>

                <div class="space-y-1">
                    <x-info.title title="Address info" />
                    <x-info.item label="Address" :value="$customer->address" />
                    <x-info.item label="City" :value="$customer->city" />
                    <x-info.item label="State" :value="$customer->state" />
                    <x-info.item label="Country" :value="$customer->country" />
                    <x-info.item label="Zip" :value="$customer->zip" />
                </div>

                <div class="space-y-1">
                    <x-info.title title="Record info" />
                    <x-info.item label="Created at" :value="$customer->created_at->diffForHumans()" />
                    <x-info.item label="Updated at" :value="$customer->updated_at->diffForHumans()" />
                </div>
            </div>
        </x-card>

        <div class="bg-base-200 col-span-2 rounded-md">
            <div class="bg-base-100 rounded-t-md p-2 space-x-4">
                <x-ui.tab href="{{ route('customers.show', [$customer, 'opportunities']) }}">
                    Opportunities
                </x-ui.tab>

                <x-ui.tab href="{{ route('customers.show', [$customer, 'tasks']) }}">
                    Tasks
                </x-ui.tab>

                <x-ui.tab href="{{ route('customers.show', [$customer, 'notes']) }}">
                    Notes
                </x-ui.tab>
            </div>

            <x-card class="bg-base-200">
                <div>
                    @livewire("customers.$tab.index", ['customer' => $customer])
                </div>
            </x-card>
        </div>
    </div>
</div>
