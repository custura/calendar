<div
    class="fixed top-0 inset-x-0 px-4 m-auto sm:inset-0 sm:p-0 sm:flex sm:items-center h-[calc(100%-1rem)] sm:justify-center"
    wire:click.stop="">

    <div class="fixed inset-0 transition-opacity" wire:click.stop="$set('isModalOpen', false)">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <div class="bg-white rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full sm:p-6"
         role="dialog"
         aria-modal="true"
         aria-labelledby="modal-headline">

        <h1 class="text-indigo-600 text-xl font-medium">
            New Appointment
        </h1>

        <div class="grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6 mt-4">
            <div class="sm:col-span-6">
                <label class="block text-sm font-medium leading-5 text-gray-700">
                    Title
                </label>
                <div class="m-1 rounded-md shadow-sm">
                    <input
                        wire:model.lazy="newAppointment.title"
                        class="border rounded p-2 block w-full sm:text-sm sm:leading-5"
                        placeholder="What's the appointment about?"
                    />
                </div>
            </div>

            <div class="sm:col-span-6">
                <label class="block text-sm font-medium leading-5 text-gray-700">
                    Notes
                </label>
                <div class="mt-1 rounded-md shadow-sm">
                    <textarea
                        rows="3"
                        wire:model.lazy="newAppointment.notes"
                        placeholder="Details regarding the appointment"
                        class="border rounded p-2 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                    ></textarea>
                </div>
            </div>

            <div class="sm:col-span-3">
                <label class="block text-sm font-medium leading-5 text-gray-700">
                    Scheduled At
                </label>
                <div class="mt-1 rounded-md shadow-sm">
                    <input
                        disabled
                        wire:model.lazy="newAppointment.scheduled_at"
                        class="border rounded p-2 block w-full sm:text-sm sm:leading-5"
                        placeholder="What's the appointment about?"
                    />
                </div>
            </div>

            <div class="sm:col-span-6">
                <label class="block text-sm font-medium leading-5 text-gray-700">
                    Start At
                </label>
                <div class="mt-1 rounded-md shadow-sm">
                    <input
                        wire:model.lazy="newAppointment.start_at"
                        class="form-input border rounded p-2 block w-full sm:text-sm sm:leading-5"
                        type="time"
                    />
                </div>
            </div>

            <div class="sm:col-span-6">
                <label class="block text-sm font-medium leading-5 text-gray-700">
                    End At
                </label>
                <div class="mt-1 rounded-md shadow-sm">
                    <input
                        wire:model.lazy="newAppointment.end_at"
                        class="form-input border rounded p-2 block w-full sm:text-sm sm:leading-5"
                        type="time"
                    />
                </div>
            </div>

            <div class="sm:col-span-3">
                <label class="block text-sm font-medium leading-5 text-gray-700">
                    Priority
                </label>
                <div class="mt-1 rounded-md shadow-sm">
                    <select
                        wire:model.lazy="newAppointment.priority"
                        class="border appearance-none bg-white rounded p-2 block w-full sm:text-sm sm:leading-5">
                        <option value="high">High</option>
                        <option value="normal">Normal</option>
                        <option value="low">Low</option>
                        <option value="high">None</option>
                    </select>
                </div>
            </div>

            <div class="sm:col-span-3">
                <label class="block text-sm font-medium leading-5 text-gray-700">
                    Project
                </label>
                <div class="mt-1 rounded-md shadow-sm">
                    <select
                        wire:model.lazy="newAppointment.project_id"
                        class="border appearance-none bg-white rounded p-2 block w-full sm:text-sm sm:leading-5">
                            <option value="Select">Select project</option>
                            @foreach($projectsBind as $project)
                              <option value="{{ $project->project_id }}">{{ $project->teamProjects->title }}</option>
                            @endforeach
                    </select>
                </div>
            </div>

            <div class="sm:col-span-3">
                <label class="block text-sm font-medium leading-5 text-gray-700">
                    Client
                </label>
                <div class="mt-1 rounded-md shadow-sm">
                    <select
                        wire:model.lazy="newAppointment.client_id"
                        class="border appearance-none bg-white rounded p-2 block w-full sm:text-sm sm:leading-5">
                            <option value="Select">Select client</option>
                            @foreach($clientsBind as $client)
                              <option value="{{ $client->client_id }}">{{ $client->teamProjectClients->client_name }}</option>
                            @endforeach
                    </select>
                </div>
            </div>

            <div class="sm:col-span-3">
                <label class="block text-sm font-medium leading-5 text-gray-700">
                    Task
                </label>
                <div class="mt-1 rounded-md shadow-sm">
                    <select
                        wire:model.lazy="newAppointment.task_id"
                        class="border appearance-none bg-white rounded p-2 block w-full sm:text-sm sm:leading-5">
                            <option value="Select">Select task</option>
                            @foreach($tasksBind as $task)
                              <option value="{{ $task->task_id }}">{{ $task->teamProjectTasks->title }}</option>
                            @endforeach
                    </select>
                </div>
            </div>

            <div class="sm:col-span-3">
                <label class="block text-sm font-medium leading-5 text-gray-700">
                    Template
                </label>
                <div class="mt-1 rounded-md shadow-sm">
                    <select
                        wire:model.lazy="newAppointment.content"
                        class="border appearance-none bg-white rounded p-2 block w-full sm:text-sm sm:leading-5">
                            <option value="Select">Select template</option>
                            @foreach($templatesBind as $template)
                              <option value="{{ $template->template_id }}">{{ $template->teamProjectTemplates->title }}</option>
                            @endforeach
                    </select>
                </div>
            </div>

        </div>

        <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
            <div class="flex w-full rounded-md shadow-sm sm:col-start-2">
                <button
                    type="button"
                    wire:click.prevent="saveAppointment"
                    class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                    Confirm
                </button>
            </div>
            <div class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:col-start-1">
                <button
                    type="button"
                    wire:click="$set('isModalOpen', false)"
                    class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
