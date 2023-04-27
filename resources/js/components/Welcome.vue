<template>
    <div class="flex justify-center mt-24 mb-12">
        <h1 class="text-5xl font-poppins font-bold uppercase">first catering api</h1>
    </div>
    <div class="justify-center bg-slate-300 rounded-lg w-1/2 mx-auto py-9">
        <div class="w-full flex justify-center">
            <h4 class="font-poppins uppercase font-bold text-xl inline-block">
                To get started, <a target="_blank" class="text-blue-400 underline" href="https://www.postman.com/downloads/">download PostMan</a>
            </h4>
        </div>

        <div class="w-full flex justify-center my-4">
            <ul>
                <li>
                    <p class="font-poppins">In order to access any routes, you will require a login token</p>
                </li>
            </ul>
        </div>
        <div class="flex min-h-full flex-col justify-center px-6 lg:px-8">

            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <form class="space-y-6" action="#" method="POST">
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                        </div>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign
                            in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>


