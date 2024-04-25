<template>
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Quản lí sản phẩm</div>
                    <a
                        class="nav-link collapsed"
                        href="#"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseProducts"
                        aria-expanded="false"
                        aria-controls="collapseProducts"
                    >
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-columns"></i>
                        </div>
                        Sản phẩm
                        <div class="sb-sidenav-collapse-arrow">
                            <i class="fas fa-angle-down"></i>
                        </div>
                    </a>
                    <div
                        class="collapse"
                        id="collapseProducts"
                        aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion"
                    >
                        <nav class="sb-sidenav-menu-nested nav">
                            <router-link to="/admin/products" class="nav-link"
                                ><i
                                    class="fas fa-box-open icon"
                                    style="margin-right: 10px"
                                ></i>
                                Danh sách sản phẩm</router-link
                            >
                            <router-link to="/admin/categories" class="nav-link"
                                ><i
                                    class="fas fa-list"
                                    style="margin-right: 10px"
                                ></i>
                                Danh sách thể loại</router-link
                            >
                            <router-link to="/admin/tags" class="nav-link"
                                ><i
                                    class="fas fa-tags"
                                    style="margin-right: 10px"
                                ></i>
                                Quản lí tags</router-link
                            >
                            <router-link to="/admin/coupon" class="nav-link"
                                ><i
                                    class="fas fa-percent"
                                    style="margin-right: 10px"
                                ></i>
                                Coupon</router-link
                            >
                        </nav>
                    </div>
                    <router-link class="nav-link" to="/admin/orders">
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        Danh sách Đơn hàng</router-link
                    >

                    <div class="sb-sidenav-menu-heading">Quản lí trang</div>
                    <a
                        class="nav-link collapsed"
                        href="#"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseLayouts"
                        aria-expanded="false"
                        aria-controls="collapseLayouts"
                    >
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-columns"></i>
                        </div>
                        Nội dung trang
                        <div class="sb-sidenav-collapse-arrow">
                            <i class="fas fa-angle-down"></i>
                        </div>
                    </a>
                    <div
                        class="collapse"
                        id="collapseLayouts"
                        aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion"
                    >
                        <nav class="sb-sidenav-menu-nested nav">
                            <router-link
                                to="/admin/manage-setting"
                                class="nav-link"
                                ><i
                                    class="fas fa-cogs"
                                    style="margin-right: 10px"
                                ></i
                                >Setting</router-link
                            >
                            <router-link
                                to="/admin/manage-slider"
                                class="nav-link"
                                ><i
                                    class="fas fa-sliders-h"
                                    style="margin-right: 10px"
                                ></i
                                >Sliders</router-link
                            >
                            <router-link
                                to="/admin/manage-menu"
                                class="nav-link"
                                ><i
                                    class="fas fa-bars"
                                    style="margin-right: 10px"
                                ></i>
                                Menu</router-link
                            >
                        </nav>
                    </div>
                    <a
                        class="nav-link collapsed"
                        href="#"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseAdmin"
                        aria-expanded="false"
                        aria-controls="collapseAdmin"
                    >
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-columns"></i>
                        </div>
                        Tài khoản và quyền
                        <div class="sb-sidenav-collapse-arrow">
                            <i class="fas fa-angle-down"></i>
                        </div>
                    </a>
                    <div
                        class="collapse"
                        id="collapseAdmin"
                        aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion"
                    >
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="layout-static.html"
                                ><i
                                    class="fas fa-user"
                                    style="margin-right: 10px"
                                ></i>
                                Tài khoản admin</a
                            >
                            <a class="nav-link" href="layout-sidenav-light.html"
                                ><i
                                    class="fas fa-user-lock"
                                    style="margin-right: 10px"
                                ></i>
                                Quyền truy cập</a
                            >
                        </nav>
                    </div>
                    <div class="sb-sidenav-menu-heading">Khách hàng</div>
                    <router-link to="/admin/manage-account" class="nav-link"
                        ><div class="sb-nav-link-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        Danh sách tài khoản</router-link
                    >
                    <router-link to="/admin/orderHistory" class="nav-link"
                        ><div class="sb-nav-link-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        Lịch sử đơn hàng</router-link
                    >
                </div>
            </div>
        </nav>
    </div>
</template>
