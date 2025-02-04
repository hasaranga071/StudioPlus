<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-white" arial-label="Furni navigation bar">
	<div class="container">
	<ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
		<div class="image-container">
			<img  width="100px" height="100px" src="/logo/S_1.PNG"/>
			<div class="companybrand">STUDIO plus +<div>
		</div>
		
	</ul>	
	<div id='sname' class="navbar-brand">MY STUDIO</div>
	<div class="collapse navbar-collapse">
		<ul class="custom-navbar-nav navbar-nav ">
			<li _class="nav-item active"> <a class="navbar-link" href="index.html">Dashboard</</li>
			<li><a class="navbar-link" href="/orders">Orders</a></li>
			<li><a class="navbar-link" href="about.html">My Business</a></li>
			<li><a class="navbar-link" href="services.html">Configurations</a></li>
		</ul>
		<ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
			<button class="btn-primary" style="border-radius:5px" type="button" onclick="window.open('/neworder')">NEW ORDER</button>
		</ul>
		<ul _class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
			<div class="logindetails_container">
                <!--start login panel -->
                <!-- <div>
                    <div _class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}">
                            <x-application-logo _class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    </div> -->

                    <!-- Navigation Links -->
                    <!-- <div _class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    </div>
                </div> -->

                            <!-- Settings Dropdown -->
                            <div _class="hidden sm:flex sm:items-center sm:ms-6">
                                <x-dropdown _align="right" width="48">
                                    <x-slot name="trigger">
                                        <div _class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                            <div>{{ Auth::user()->name }}</div>

                                            <div style=" border: 2px solid #555;border-radius:4px">
                                            <img  width="50px" height="50px" src="{{ asset('images/people.png') }}"/>
                                            </div>
                                        </div>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('profile.edit')">
                                            {{ __('Profile') }}
                                        </x-dropdown-link>

                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf

                                            <x-dropdown-link :href="route('logout')"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>



                    <!-- Responsive Navigation Menu -->

                        <!-- <div class="pt-2 pb-3 space-y-1">
                            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-responsive-nav-link>
                        </div> -->

                        <!-- Responsive Settings Options -->
                        <div _class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                            <!-- <div class="px-4">
                                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                            </div> -->

                            <div _class="mt-3 space-y-1">
                                <!-- <x-responsive-nav-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-responsive-nav-link> -->

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-responsive-nav-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Logout') }}
                                    </x-responsive-nav-link>
                                </form>
                            </div>
                        </div>



                <!-- end login panel -->
                
			</div>
		</ul>
	</div>
</div>			
</nav>
<script>
        $(document).ready(function () {
        var userkey="{{ auth()->user()->id }}";
            

        axios.get('/studiodetails_of_user', {
            params: {
                UserKey: 1
            }
        })
        .then(response => {
            //console.log("xxxxxxxxxxxxxx=",response.data); // Handle the response data
            //alert(response.data[0]['studioname'])
            document.getElementById("sname").innerHTML=response.data[0]['studioname'];
        })
        .catch(error => {
            console.error("There was an error!", error);
        });
    

        });
        </script>