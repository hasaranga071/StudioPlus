<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

			<div _class="container">

				<a class="navbar-brand" href="index.html">[STUDIO +]</a></br>
                <div class="navbar-brand">STUDIO NAME - TOWN</div>

				<!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button> -->

				<div class="collapse navbar-collapse" id="navbarsFurni">
					<ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
						<li class="nav-item active">
                            <div style='display:inline'>
                                <!-- <img src="{{ asset('images/dashboard.png') }}" width="15px" height="15px"/> -->
							    <a class="nav-link" href="index.html">Dashboard</a>
                            </div>
						</li>
						<li><a class="nav-link" href="shop.html">Orders</a></li>
						<li><a class="nav-link" href="about.html">My Business</a></li>
						<li><a class="nav-link" href="services.html">Configurations</a></li>

					</ul>
					<ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">

						<button style="border-radius:5px" type="button" onclick="window.open('/neworder')">NEW ORDER</button>
					</ul>
					<ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
						<li><a class="nav-link" href="#"><img src="{{ asset('images/user.svg') }}"></a></li>
						<!-- <li><a class="nav-link" href="cart.html"><img src="images/cart.svg"></a></li> -->
					</ul>
				</div>
			</div>

              <!-- Settings Dropdown -->
              <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
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

		</nav>
