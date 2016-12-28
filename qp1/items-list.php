<!doctype html>
<html class="no-js" lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title> ModularAdmin - Free Dashboard Theme | HTML Version </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->
        <link rel="stylesheet" href="css/vendor.css">
        <!-- Theme initialization -->
        <script>
            var themeSettings = (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) :
            {};
            var themeName = themeSettings.themeName || '';
            if (themeName)
            {
                document.write('<link rel="stylesheet" id="theme-style" href="css/app-' + themeName + '.css">');
            }
            else
            {
                document.write('<link rel="stylesheet" id="theme-style" href="css/app.css">');
            }
        </script>
    </head>

    <body>
        <div class="main-wrapper">
            <div class="app" id="app">
                <header class="header">
                    <div class="header-block header-block-collapse hidden-lg-up"> <button class="collapse-btn" id="sidebar-collapse-btn">
    			<i class="fa fa-bars"></i>
    		</button> </div>
                    <div class="header-block header-block-search hidden-sm-down">
                        <form role="search">
                            <div class="input-container"> <i class="fa fa-search"></i> <input type="search" placeholder="Search">
                                <div class="underline"></div>
                            </div>
                        </form>
                    </div>
                    <div class="header-block header-block-buttons">
                        <a href="https://github.com/modularcode/modular-admin-html" class="btn btn-oval btn-sm rounded-s header-btn"> <i class="fa fa-github-alt"></i><?php echo "Hi" ?> View on GitHub </a>
                        <a href="https://github.com/modularcode/modular-admin-html/releases/download/v1.0.1/modular-admin-html-1.0.1.zip" class="btn btn-oval btn-sm rounded-s header-btn"> <i class="fa fa-cloud-download"></i> Download .zip </a>
                    </div>
                    <div class="header-block header-block-nav">
                        <ul class="nav-profile">
                            <li class="notifications new">
                                <a href="" data-toggle="dropdown"> <i class="fa fa-bell-o"></i> <sup>
    			      <span class="counter">8</span>
    			    </sup> </a>
                                <div class="dropdown-menu notifications-dropdown-menu">
                                    <ul class="notifications-container">
                                        <li>
                                            <a href="" class="notification-item">
                                                <div class="img-col">
                                                    <div class="img" style="background-image: url('assets/faces/3.jpg')"></div>
                                                </div>
                                                <div class="body-col">
                                                    <p> <span class="accent">Zack Alien</span> pushed new commit: <span class="accent">Fix page load performance issue</span>. </p>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="" class="notification-item">
                                                <div class="img-col">
                                                    <div class="img" style="background-image: url('assets/faces/5.jpg')"></div>
                                                </div>
                                                <div class="body-col">
                                                    <p> <span class="accent">Amaya Hatsumi</span> started new task: <span class="accent">Dashboard UI design.</span>. </p>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="" class="notification-item">
                                                <div class="img-col">
                                                    <div class="img" style="background-image: url('assets/faces/8.jpg')"></div>
                                                </div>
                                                <div class="body-col">
                                                    <p> <span class="accent">Andy Nouman</span> deployed new version of <span class="accent">NodeJS REST Api V3</span> </p>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                    <footer>
                                        <ul>
                                            <li> <a href="">
    			            View All
    			          </a> </li>
                                        </ul>
                                    </footer>
                                </div>
                            </li>
                            <li class="profile dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    <div class="img" style="background-image: url('https://avatars3.githubusercontent.com/u/3959008?v=3&s=40')"> </div> <span class="name">
    			      John Doe
    			    </span> </a>
                                <div class="dropdown-menu profile-dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <a class="dropdown-item" href="#"> <i class="fa fa-user icon"></i> Profile </a>
                                    <a class="dropdown-item" href="#"> <i class="fa fa-bell icon"></i> Notifications </a>
                                    <a class="dropdown-item" href="#"> <i class="fa fa-gear icon"></i> Settings </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="login.html"> <i class="fa fa-power-off icon"></i> Logout </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </header>
                <aside class="sidebar">
                    <div class="sidebar-container">
                        <div class="sidebar-header">
                            <div class="brand">
                                <div class="logo"> <span class="l l1"></span> <span class="l l2"></span> <span class="l l3"></span> <span class="l l4"></span> <span class="l l5"></span> </div> Modular Admin </div>
                        </div>
                        <nav class="menu">
                            <ul class="nav metismenu" id="sidebar-menu">
                                <li>
                                    <a href="index.html"> <i class="fa fa-home"></i> Dashboard </a>
                                </li>
                                <li class="active open">
                                    <a href=""> <i class="fa fa-th-large"></i> Items Manager <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li class="active"> <a href="items-list.html">
    								Items List
    							</a> </li>
                                        <li> <a href="item-editor.html">
    								Item Editor
    							</a> </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href=""> <i class="fa fa-bar-chart"></i> Charts <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a href="charts-flot.html">
    								Flot Charts
    							</a> </li>
                                        <li> <a href="charts-morris.html">
    								Morris Charts
    							</a> </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href=""> <i class="fa fa-table"></i> Tables <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a href="static-tables.html">
    								Static Tables
    							</a> </li>
                                        <li> <a href="responsive-tables.html">
    								Responsive Tables
    							</a> </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="forms.html"> <i class="fa fa-pencil-square-o"></i> Forms </a>
                                </li>
                                <li>
                                    <a href=""> <i class="fa fa-desktop"></i> UI Elements <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a href="buttons.html">
    								Buttons
    							</a> </li>
                                        <li> <a href="cards.html">
    								Cards
    							</a> </li>
                                        <li> <a href="typography.html">
    								Typography
    							</a> </li>
                                        <li> <a href="icons.html">
    								Icons
    							</a> </li>
                                        <li> <a href="grid.html">
    								Grid
    							</a> </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href=""> <i class="fa fa-file-text-o"></i> Pages <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a href="login.html">
    								Login
    							</a> </li>
                                        <li> <a href="signup.html">
    								Sign Up
    							</a> </li>
                                        <li> <a href="reset.html">
    								Reset
    							</a> </li>
                                        <li> <a href="error-404.html">
    								Error 404 App
    							</a> </li>
                                        <li> <a href="error-404-alt.html">
    								Error 404 Global
    							</a> </li>
                                        <li> <a href="error-500.html">
    								Error 500 App
    							</a> </li>
                                        <li> <a href="error-500-alt.html">
    								Error 500 Global
    							</a> </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="https://github.com/modularcode/modular-admin-html"> <i class="fa fa-github-alt"></i> Theme Docs </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <footer class="sidebar-footer">
                        <ul class="nav metismenu" id="customize-menu">
                            <li>
                                <ul>
                                    <li class="customize">
                                        <div class="customize-item">
                                            <div class="row customize-header">
                                                <div class="col-xs-4"> </div>
                                                <div class="col-xs-4"> <label class="title">fixed</label> </div>
                                                <div class="col-xs-4"> <label class="title">static</label> </div>
                                            </div>
                                            <div class="row hidden-md-down">
                                                <div class="col-xs-4"> <label class="title">Sidebar:</label> </div>
                                                <div class="col-xs-4"> <label>
    				                        <input class="radio" type="radio" name="sidebarPosition" value="sidebar-fixed" >
    				                        <span></span>
    				                    </label> </div>
                                                <div class="col-xs-4"> <label>
    				                        <input class="radio" type="radio" name="sidebarPosition" value="">
    				                        <span></span>
    				                    </label> </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-4"> <label class="title">Header:</label> </div>
                                                <div class="col-xs-4"> <label>
    				                        <input class="radio" type="radio" name="headerPosition" value="header-fixed">
    				                        <span></span>
    				                    </label> </div>
                                                <div class="col-xs-4"> <label>
    				                        <input class="radio" type="radio" name="headerPosition" value="">
    				                        <span></span>
    				                    </label> </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-4"> <label class="title">Footer:</label> </div>
                                                <div class="col-xs-4"> <label>
    				                        <input class="radio" type="radio" name="footerPosition" value="footer-fixed">
    				                        <span></span>
    				                    </label> </div>
                                                <div class="col-xs-4"> <label>
    				                        <input class="radio" type="radio" name="footerPosition" value="">
    				                        <span></span>
    				                    </label> </div>
                                            </div>
                                        </div>
                                        <div class="customize-item">
                                            <ul class="customize-colors">
                                                <li> <span class="color-item color-red" data-theme="red"></span> </li>
                                                <li> <span class="color-item color-orange" data-theme="orange"></span> </li>
                                                <li> <span class="color-item color-green" data-theme="green"></span> </li>
                                                <li> <span class="color-item color-seagreen" data-theme="seagreen"></span> </li>
                                                <li> <span class="color-item color-blue active" data-theme=""></span> </li>
                                                <li> <span class="color-item color-purple" data-theme="purple"></span> </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                                <a href=""> <i class="fa fa-cog"></i> Customize </a>
                            </li>
                        </ul>
                    </footer>
                </aside>
                <div class="sidebar-overlay" id="sidebar-overlay"></div>
                <article class="content items-list-page">
                    <div class="title-search-block">
                        <div class="title-block">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="title">
					Items
					<a href="item-editor.html" class="btn btn-primary btn-sm rounded-s">
						Add New
					</a><!--
				 --><div class="action dropdown">
						<button class="btn  btn-sm rounded-s btn-secondary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							More actions...
						</button>
						<div class="dropdown-menu" aria-labelledby="dropdownMenu1">
							<a class="dropdown-item" href="#"><i class="fa fa-pencil-square-o icon"></i>Mark as a draft</a>
							<a class="dropdown-item" href="#" data-toggle="modal" data-target="#confirm-modal"><i class="fa fa-close icon"></i>Delete</a>
						</div>
					</div>
				</h3>
                                    <p class="title-description"> List of sample items - e.g. books, movies, events, etc... </p>
                                </div>
                            </div>
                        </div>
                        <div class="items-search">
                            <form class="form-inline">
                                <div class="input-group"> <input type="text" class="form-control boxed rounded-s" placeholder="Search for..."> <span class="input-group-btn">
					<button class="btn btn-secondary rounded-s" type="button">
						<i class="fa fa-search"></i>
					</button>
				</span> </div>
                            </form>
                        </div>
                    </div>
                    <div class="card items">
                        <ul class="item-list striped">
                            <li class="item item-list-header hidden-sm-down">
                                <div class="item-row">
                                    <div class="item-col fixed item-col-check"> <label class="item-check" id="select-all-items">
						<input type="checkbox" class="checkbox">
						<span></span>
					</label> </div>
                                    <div class="item-col item-col-header fixed item-col-img md">
                                        <div> <span>Media</span> </div>
                                    </div>
                                    <div class="item-col item-col-header item-col-title">
                                        <div> <span>Name</span> </div>
                                    </div>
                                    <div class="item-col item-col-header item-col-sales">
                                        <div> <span>Sales</span> </div>
                                    </div>
                                    <div class="item-col item-col-header item-col-stats">
                                        <div class="no-overflow"> <span>Stats</span> </div>
                                    </div>
                                    <div class="item-col item-col-header item-col-category">
                                        <div class="no-overflow"> <span>Category</span> </div>
                                    </div>
                                    <div class="item-col item-col-header item-col-author">
                                        <div class="no-overflow"> <span>Author</span> </div>
                                    </div>
                                    <div class="item-col item-col-header item-col-date">
                                        <div> <span>Published</span> </div>
                                    </div>
                                    <div class="item-col item-col-header fixed item-col-actions-dropdown"> </div>
                                </div>
                            </li>
                            <li class="item">
                                <div class="item-row">
                                    <div class="item-col fixed item-col-check"> <label class="item-check" id="select-all-items">
							<input type="checkbox" class="checkbox">
							<span></span>
						</label> </div>
                                    <div class="item-col fixed item-col-img md">
                                        <a href="item-editor.html">
                                            <div class="item-img rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/brad_frost/128.jpg)"></div>
                                        </a>
                                    </div>
                                    <div class="item-col fixed pull-left item-col-title">
                                        <div class="item-heading">Name</div>
                                        <div>
                                            <a href="item-editor.html" class="">
                                                <h4 class="item-title">
									12 Myths Uncovered About IT &amp; Software
								</h4> </a>
                                        </div>
                                    </div>
                                    <div class="item-col item-col-sales">
                                        <div class="item-heading">Sales</div>
                                        <div> 46323 </div>
                                    </div>
                                    <div class="item-col item-col-stats no-overflow">
                                        <div class="item-heading">Stats</div>
                                        <div class="no-overflow">
                                            <div class="item-stats sparkline" data-type="bar"></div>
                                        </div>
                                    </div>
                                    <div class="item-col item-col-category no-overflow">
                                        <div class="item-heading">Category</div>
                                        <div class="no-overflow"> <a href="">Software</a> </div>
                                    </div>
                                    <div class="item-col item-col-author">
                                        <div class="item-heading">Author</div>
                                        <div class="no-overflow"> <a href="">Meadow Katheryne</a> </div>
                                    </div>
                                    <div class="item-col item-col-date">
                                        <div class="item-heading">Published</div>
                                        <div class="no-overflow"> 21 SEP 10:45 </div>
                                    </div>
                                    <div class="item-col fixed item-col-actions-dropdown">
                                        <div class="item-actions-dropdown">
                                            <a class="item-actions-toggle-btn"> <span class="inactive">
									<i class="fa fa-cog"></i>
								</span> <span class="active">
								<i class="fa fa-chevron-circle-right"></i>
								</span> </a>
                                            <div class="item-actions-block">
                                                <ul class="item-actions-list">
                                                    <li>
                                                        <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal"> <i class="fa fa-trash-o "></i> </a>
                                                    </li>
                                                    <li>
                                                        <a class="edit" href="item-editor.html"> <i class="fa fa-pencil"></i> </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <div class="item-row">
                                    <div class="item-col fixed item-col-check"> <label class="item-check" id="select-all-items">
							<input type="checkbox" class="checkbox">
							<span></span>
						</label> </div>
                                    <div class="item-col fixed item-col-img md">
                                        <a href="item-editor.html">
                                            <div class="item-img rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/_everaldo/128.jpg)"></div>
                                        </a>
                                    </div>
                                    <div class="item-col fixed pull-left item-col-title">
                                        <div class="item-heading">Name</div>
                                        <div>
                                            <a href="item-editor.html" class="">
                                                <h4 class="item-title">
									50% of things doesn&#x27;t really belongs to you
								</h4> </a>
                                        </div>
                                    </div>
                                    <div class="item-col item-col-sales">
                                        <div class="item-heading">Sales</div>
                                        <div> 4567 </div>
                                    </div>
                                    <div class="item-col item-col-stats no-overflow">
                                        <div class="item-heading">Stats</div>
                                        <div class="no-overflow">
                                            <div class="item-stats sparkline" data-type="bar"></div>
                                        </div>
                                    </div>
                                    <div class="item-col item-col-category no-overflow">
                                        <div class="item-heading">Category</div>
                                        <div class="no-overflow"> <a href="">Hardware</a> </div>
                                    </div>
                                    <div class="item-col item-col-author">
                                        <div class="item-heading">Author</div>
                                        <div class="no-overflow"> <a href="">Alexander Sargssyan</a> </div>
                                    </div>
                                    <div class="item-col item-col-date">
                                        <div class="item-heading">Published</div>
                                        <div class="no-overflow"> 21 SEP 10:45 </div>
                                    </div>
                                    <div class="item-col fixed item-col-actions-dropdown">
                                        <div class="item-actions-dropdown">
                                            <a class="item-actions-toggle-btn"> <span class="inactive">
									<i class="fa fa-cog"></i>
								</span> <span class="active">
								<i class="fa fa-chevron-circle-right"></i>
								</span> </a>
                                            <div class="item-actions-block">
                                                <ul class="item-actions-list">
                                                    <li>
                                                        <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal"> <i class="fa fa-trash-o "></i> </a>
                                                    </li>
                                                    <li>
                                                        <a class="edit" href="item-editor.html"> <i class="fa fa-pencil"></i> </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <div class="item-row">
                                    <div class="item-col fixed item-col-check"> <label class="item-check" id="select-all-items">
							<input type="checkbox" class="checkbox">
							<span></span>
						</label> </div>
                                    <div class="item-col fixed item-col-img md">
                                        <a href="item-editor.html">
                                            <div class="item-img rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/eduardo_olv/128.jpg)"></div>
                                        </a>
                                    </div>
                                    <div class="item-col fixed pull-left item-col-title">
                                        <div class="item-heading">Name</div>
                                        <div>
                                            <a href="item-editor.html" class="">
                                                <h4 class="item-title">
									Vestibulum tincidunt amet laoreet mauris sit sem aliquam cras maecenas vel aliquam.
								</h4> </a>
                                        </div>
                                    </div>
                                    <div class="item-col item-col-sales">
                                        <div class="item-heading">Sales</div>
                                        <div> 854 </div>
                                    </div>
                                    <div class="item-col item-col-stats no-overflow">
                                        <div class="item-heading">Stats</div>
                                        <div class="no-overflow">
                                            <div class="item-stats sparkline" data-type="bar"></div>
                                        </div>
                                    </div>
                                    <div class="item-col item-col-category no-overflow">
                                        <div class="item-heading">Category</div>
                                        <div class="no-overflow"> <a href="">Anywhere</a> </div>
                                    </div>
                                    <div class="item-col item-col-author">
                                        <div class="item-heading">Author</div>
                                        <div class="no-overflow"> <a href="">Some Long Author Name</a> </div>
                                    </div>
                                    <div class="item-col item-col-date">
                                        <div class="item-heading">Published</div>
                                        <div class="no-overflow"> 21 SEP 10:45 </div>
                                    </div>
                                    <div class="item-col fixed item-col-actions-dropdown">
                                        <div class="item-actions-dropdown">
                                            <a class="item-actions-toggle-btn"> <span class="inactive">
									<i class="fa fa-cog"></i>
								</span> <span class="active">
								<i class="fa fa-chevron-circle-right"></i>
								</span> </a>
                                            <div class="item-actions-block">
                                                <ul class="item-actions-list">
                                                    <li>
                                                        <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal"> <i class="fa fa-trash-o "></i> </a>
                                                    </li>
                                                    <li>
                                                        <a class="edit" href="item-editor.html"> <i class="fa fa-pencil"></i> </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <div class="item-row">
                                    <div class="item-col fixed item-col-check"> <label class="item-check" id="select-all-items">
							<input type="checkbox" class="checkbox">
							<span></span>
						</label> </div>
                                    <div class="item-col fixed item-col-img md">
                                        <a href="item-editor.html">
                                            <div class="item-img rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/why_this/128.jpg)"></div>
                                        </a>
                                    </div>
                                    <div class="item-col fixed pull-left item-col-title">
                                        <div class="item-heading">Name</div>
                                        <div>
                                            <a href="item-editor.html" class="">
                                                <h4 class="item-title">
									Lorem Ipsum is not simply random text
								</h4> </a>
                                        </div>
                                    </div>
                                    <div class="item-col item-col-sales">
                                        <div class="item-heading">Sales</div>
                                        <div> 1861 </div>
                                    </div>
                                    <div class="item-col item-col-stats no-overflow">
                                        <div class="item-heading">Stats</div>
                                        <div class="no-overflow">
                                            <div class="item-stats sparkline" data-type="bar"></div>
                                        </div>
                                    </div>
                                    <div class="item-col item-col-category no-overflow">
                                        <div class="item-heading">Category</div>
                                        <div class="no-overflow"> <a href="">Something</a> </div>
                                    </div>
                                    <div class="item-col item-col-author">
                                        <div class="item-heading">Author</div>
                                        <div class="no-overflow"> <a href="">Willard Bennett</a> </div>
                                    </div>
                                    <div class="item-col item-col-date">
                                        <div class="item-heading">Published</div>
                                        <div class="no-overflow"> 21 SEP 10:45 </div>
                                    </div>
                                    <div class="item-col fixed item-col-actions-dropdown">
                                        <div class="item-actions-dropdown">
                                            <a class="item-actions-toggle-btn"> <span class="inactive">
									<i class="fa fa-cog"></i>
								</span> <span class="active">
								<i class="fa fa-chevron-circle-right"></i>
								</span> </a>
                                            <div class="item-actions-block">
                                                <ul class="item-actions-list">
                                                    <li>
                                                        <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal"> <i class="fa fa-trash-o "></i> </a>
                                                    </li>
                                                    <li>
                                                        <a class="edit" href="item-editor.html"> <i class="fa fa-pencil"></i> </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <div class="item-row">
                                    <div class="item-col fixed item-col-check"> <label class="item-check" id="select-all-items">
							<input type="checkbox" class="checkbox">
							<span></span>
						</label> </div>
                                    <div class="item-col fixed item-col-img md">
                                        <a href="item-editor.html">
                                            <div class="item-img rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/w7download/128.jpg)"></div>
                                        </a>
                                    </div>
                                    <div class="item-col fixed pull-left item-col-title">
                                        <div class="item-heading">Name</div>
                                        <div>
                                            <a href="item-editor.html" class="">
                                                <h4 class="item-title">
									Ut dui quis amet curabitur vestibulum
								</h4> </a>
                                        </div>
                                    </div>
                                    <div class="item-col item-col-sales">
                                        <div class="item-heading">Sales</div>
                                        <div> 7891 </div>
                                    </div>
                                    <div class="item-col item-col-stats no-overflow">
                                        <div class="item-heading">Stats</div>
                                        <div class="no-overflow">
                                            <div class="item-stats sparkline" data-type="bar"></div>
                                        </div>
                                    </div>
                                    <div class="item-col item-col-category no-overflow">
                                        <div class="item-heading">Category</div>
                                        <div class="no-overflow"> <a href="">Something Else</a> </div>
                                    </div>
                                    <div class="item-col item-col-author">
                                        <div class="item-heading">Author</div>
                                        <div class="no-overflow"> <a href="">Ivy Lorrie</a> </div>
                                    </div>
                                    <div class="item-col item-col-date">
                                        <div class="item-heading">Published</div>
                                        <div class="no-overflow"> 21 SEP 10:45 </div>
                                    </div>
                                    <div class="item-col fixed item-col-actions-dropdown">
                                        <div class="item-actions-dropdown">
                                            <a class="item-actions-toggle-btn"> <span class="inactive">
									<i class="fa fa-cog"></i>
								</span> <span class="active">
								<i class="fa fa-chevron-circle-right"></i>
								</span> </a>
                                            <div class="item-actions-block">
                                                <ul class="item-actions-list">
                                                    <li>
                                                        <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal"> <i class="fa fa-trash-o "></i> </a>
                                                    </li>
                                                    <li>
                                                        <a class="edit" href="item-editor.html"> <i class="fa fa-pencil"></i> </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <div class="item-row">
                                    <div class="item-col fixed item-col-check"> <label class="item-check" id="select-all-items">
							<input type="checkbox" class="checkbox">
							<span></span>
						</label> </div>
                                    <div class="item-col fixed item-col-img md">
                                        <a href="item-editor.html">
                                            <div class="item-img rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/pankogut/128.jpg)"></div>
                                        </a>
                                    </div>
                                    <div class="item-col fixed pull-left item-col-title">
                                        <div class="item-heading">Name</div>
                                        <div>
                                            <a href="item-editor.html" class="">
                                                <h4 class="item-title">
									Mus sociosqu etiam autem rutrum at molestie elit pulvinar
								</h4> </a>
                                        </div>
                                    </div>
                                    <div class="item-col item-col-sales">
                                        <div class="item-heading">Sales</div>
                                        <div> 95150 </div>
                                    </div>
                                    <div class="item-col item-col-stats no-overflow">
                                        <div class="item-heading">Stats</div>
                                        <div class="no-overflow">
                                            <div class="item-stats sparkline" data-type="bar"></div>
                                        </div>
                                    </div>
                                    <div class="item-col item-col-category no-overflow">
                                        <div class="item-heading">Category</div>
                                        <div class="no-overflow"> <a href="">Other</a> </div>
                                    </div>
                                    <div class="item-col item-col-author">
                                        <div class="item-heading">Author</div>
                                        <div class="no-overflow"> <a href="">Evander Archie</a> </div>
                                    </div>
                                    <div class="item-col item-col-date">
                                        <div class="item-heading">Published</div>
                                        <div class="no-overflow"> 21 SEP 10:45 </div>
                                    </div>
                                    <div class="item-col fixed item-col-actions-dropdown">
                                        <div class="item-actions-dropdown">
                                            <a class="item-actions-toggle-btn"> <span class="inactive">
									<i class="fa fa-cog"></i>
								</span> <span class="active">
								<i class="fa fa-chevron-circle-right"></i>
								</span> </a>
                                            <div class="item-actions-block">
                                                <ul class="item-actions-list">
                                                    <li>
                                                        <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal"> <i class="fa fa-trash-o "></i> </a>
                                                    </li>
                                                    <li>
                                                        <a class="edit" href="item-editor.html"> <i class="fa fa-pencil"></i> </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <nav class="text-xs-right">
                        <ul class="pagination">
                            <li class="page-item"> <a class="page-link" href="">
				Prev
			</a> </li>
                            <li class="page-item active"> <a class="page-link" href="">
				1
			</a> </li>
                            <li class="page-item"> <a class="page-link" href="">
				2
			</a> </li>
                            <li class="page-item"> <a class="page-link" href="">
				3
			</a> </li>
                            <li class="page-item"> <a class="page-link" href="">
				4
			</a> </li>
                            <li class="page-item"> <a class="page-link" href="">
				5
			</a> </li>
                            <li class="page-item"> <a class="page-link" href="">
				Next
			</a> </li>
                        </ul>
                    </nav>
                </article>
                <footer class="footer">
                    <div class="footer-block buttons"> <iframe class="footer-github-btn" src="https://ghbtns.com/github-btn.html?user=modularcode&repo=modular-admin-html&type=star&count=true" frameborder="0" scrolling="0" width="140px" height="20px"></iframe> </div>
                    <div class="footer-block author">
                        <ul>
                            <li> created by <a href="https://github.com/modularcode">ModularCode</a> </li>
                            <li> <a href="https://github.com/modularcode/modular-admin-html#get-in-touch">get in touch</a> </li>
                        </ul>
                    </div>
                </footer>
                <div class="modal fade" id="modal-media">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    					<span aria-hidden="true">&times;</span>
    					<span class="sr-only">Close</span>
    				</button>
                                <h4 class="modal-title">Media Library</h4> </div>
                            <div class="modal-body modal-tab-container">
                                <ul class="nav nav-tabs modal-tabs" role="tablist">
                                    <li class="nav-item"> <a class="nav-link" href="#gallery" data-toggle="tab" role="tab">Gallery</a> </li>
                                    <li class="nav-item"> <a class="nav-link active" href="#upload" data-toggle="tab" role="tab">Upload</a> </li>
                                </ul>
                                <div class="tab-content modal-tab-content">
                                    <div class="tab-pane fade" id="gallery" role="tabpanel">
                                        <div class="images-container">
                                            <div class="row"> </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade active in" id="upload" role="tabpanel">
                                        <div class="upload-container">
                                            <div id="dropzone">
                                                <form action="/" method="POST" enctype="multipart/form-data" class="dropzone needsclick dz-clickable" id="demo-upload">
                                                    <div class="dz-message-block">
                                                        <div class="dz-message needsclick"> Drop files here or click to upload. </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer"> <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> <button type="button" class="btn btn-primary">Insert Selected</button> </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                <div class="modal fade" id="confirm-modal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    					<span aria-hidden="true">&times;</span>
    				</button>
                                <h4 class="modal-title"><i class="fa fa-warning"></i> Alert</h4> </div>
                            <div class="modal-body">
                                <p>Are you sure want to do this?</p>
                            </div>
                            <div class="modal-footer"> <button type="button" class="btn btn-primary" data-dismiss="modal">Yes</button> <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button> </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
            </div>
        </div>
        <!-- Reference block for JS -->
        <div class="ref" id="ref">
            <div class="color-primary"></div>
            <div class="chart">
                <div class="color-primary"></div>
                <div class="color-secondary"></div>
            </div>
        </div>
        <script src="js/vendor.js"></script>
        <script src="js/app.js"></script>
    </body>

</html>