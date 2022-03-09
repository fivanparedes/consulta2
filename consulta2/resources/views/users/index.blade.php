<!-- 
=========================================================
 Light Bootstrap Dashboard - v2.0.1
=========================================================

 Product Page: https://www.creative-tim.com/product/light-bootstrap-dashboard
 Copyright 2019 Creative Tim (https://www.creative-tim.com) & UPDIVISION (https://www.updivision.com)
 Licensed under MIT (https://github.com/creativetimofficial/light-bootstrap-dashboard/blob/master/LICENSE)

 Coded by Creative Tim & UPDIVISION

=========================================================

 The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.  -->
 @extends('layouts.app', ['activePage' => 'users.index', 'title' => $companyName.' | Mi hoja de vida', 'navName' => 'users.index', 'activeButton' => 'laravel'])

 @section('content')
 <div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card data-tables">

                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Hoja de vida</h3>
                                <p class="text-sm mb-0">
                                    Rellene esta página con información sobre su salud y estilo de vida actuales.
                                </p>
                            </div>
                            <div class="col-4 text-right">
                                <a href="#" class="btn btn-sm btn-default">Add user</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                </div>
                <div class="card-body">

                </div>
                    {{-- <div class="card-body table-full-width table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr><th>Name</th>
                                <th>Email</th>
                                <th>Start</th>
                                <th>Actions</th>
                            </tr></thead>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Start</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            
                                                                        <tr>
                                        <td>Admin Admin</td>
                                        <td>admin@lightbp.com</td>
                                        <td>2020-02-25 12:37:04</td>
                                        <td class="d-flex justify-content-end">
                                                
                                                <a href="#"><i class="fa fa-edit"></i></a>
                                                                                        </td>
                                    </tr>
                                                                </tbody>
                        </table>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
            <footer class="footer">
<div class="container -fluid ">
    <nav>
        <ul class="footer-menu">
            <li>
                <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
            </li>
            <li>
                <a href="https://www.updivision.com" class="nav-link" target="_blank">Updivision</a>
            </li>
            <li>
                <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
            </li>
            <li>
                <a href="http://blog.creative-tim.com" class="nav-link" target="_blank">Blog</a>
            </li>
        </ul>
        <p class="copyright text-center">
            ©
            <script>
                document.write(new Date().getFullYear())
            </script>2020
            <a href="http://www.creative-tim.com">Creative Tim</a> &amp; <a href="https://www.updivision.com">Updivision</a> , made with love for a better web
        </p>
    </nav>
</div>
@endsection