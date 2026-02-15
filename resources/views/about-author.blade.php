<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">
            About the Author
        </h2>
    </x-slot>

    <div class="py-10 bg-background">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-surface border border-border rounded-2xl shadow p-8">

                <div class="flex flex-col md:flex-row items-center gap-8">

                    {{-- Profile Image --}}
                    <div class="shrink-0">
                        <img src="{{ asset('images/author.jpg') }}" alt="Author photo"
                            class="w-52 h-52 rounded-full object-cover border border-border shadow">
                    </div>

                    {{-- Info --}}
                    <div class="text-center md:text-left">

                        <h1 class="text-2xl font-bold text-text">
                            Veselin Sevo
                        </h1>

                        <p class="text-muted mt-1">
                            Index Number: 23/22
                        </p>

                        <div class="mt-6 space-y-3 text-text leading-relaxed">
                            <p>
                                The application demonstrates:
                            </p>

                            <ul class="list-disc list-inside text-muted">
                                <li>Authentication & authorization</li>
                                <li>Admin panel</li>
                                <li>Store & product management</li>
                                <li>Shopping cart & order flow</li>
                                <li>Role-based access control</li>
                            </ul>

                            <p class="mt-4">
                                Developed using Laravel 12, MySQL, Tailwind CSS
                                (and ChatGPT😄).
                            </p>

                            <p class="mt-4">
                                For more details, see the project documentation:
                                <a href="https://docs.google.com/document/d/1Ri2FKaAxVJQdgsiq_pSjqmAhIhPw5wj3sXRrKPMJTa4/edit?usp=sharing"
                                    target="_blank" class="text-accent hover:underline"> ->here </a>
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>