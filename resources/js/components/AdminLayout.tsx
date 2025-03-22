import { useState, useEffect } from 'react';
import { Link, Outlet, useLocation } from 'react-router-dom';
import './AdminLayout.scss';

interface MenuItem {
    title: string;
    link?: string;
    icon?: string;
    children?: MenuItem[];
}

const AdminLayout = () => {
    const [sidebarCollapsed, setSidebarCollapsed] = useState(false);
    const location = useLocation();

    // Menu sidebar
    const menuSideBar: MenuItem[] = [
        {
            title: "Quản lý giao diện",
            icon: "bi bi-layout-text-sidebar-reverse",
            children: [
                { title: "Quản lý banner", link: "/admin/banners", icon: "bi bi-image" },
                { title: "Quản lý header", link: "/admin/header", icon: "bi bi-card-heading" },
            ]
        },
        { title: "Quản lý sản phẩm", link: "/admin/products", icon: "bi bi-box-seam" },
        { title: "Quản lý category", link: "/admin/categories", icon: "bi bi-tags" },
    ];

    // Toggle sidebar collapse
    const toggleSidebar = () => {
        setSidebarCollapsed(!sidebarCollapsed);
    };

    // Check if a menu item is active
    const isActive = (path: string | undefined): boolean => {
        if (!path) return false;
        return location.pathname === path;
    };

    // Render menu items recursively
    const renderMenuItems = (items: MenuItem[], depth = 0) => {
        return items.map((item, index) => {
            if (item.children) {
                // Item with submenu
                const dropdownId = `dropdown-${depth}-${index}`;
                return (
                    <div key={index} className="dropdown mb-2">
                        <button
                            className="btn btn-toggle d-flex align-items-center rounded border-0 w-100 text-start"
                            data-bs-toggle="collapse"
                            data-bs-target={`#${dropdownId}`}
                            aria-expanded="false"
                        >
                            {item.icon && <i className={`me-3 ${item.icon}`}></i>}
                            <span className={`menu-title ${sidebarCollapsed ? 'd-none' : ''}`}>{item.title}</span>
                            <i className={`ms-auto bi bi-chevron-down ${sidebarCollapsed ? 'd-none' : ''}`}></i>
                        </button>
                        <div className="collapse" id={dropdownId}>
                            <div className="ps-3 border-start border-secondary border-opacity-25 ms-3 mt-1">
                                {renderMenuItems(item.children, depth + 1)}
                            </div>
                        </div>
                    </div>
                );
            } else {
                // Simple menu item
                return (
                    <div key={index}>
                        <Link
                            to={item.link || '#'}
                            className={`btn d-flex align-items-center rounded border-0 w-100 text-start mb-2 ${isActive(item.link) ? 'active bg-secondary bg-opacity-25' : ''}`}
                        >
                            {item.icon && <i className={`me-3 ${item.icon}`}></i>}
                            <span className={`menu-title ${sidebarCollapsed ? 'd-none' : ''}`}>{item.title}</span>
                        </Link>
                    </div>
                );
            }
        });
    };

    useEffect(() => {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css';
        document.head.appendChild(link);

        return () => {
            document.head.removeChild(link);
        };
    }, [location]);

    return (
        <div className="d-flex flex-column min-vh-100">
            {/* Navbar */}
            <nav className="navbar navbar-expand-lg" style={{ backgroundColor: '#151614' }}>
                <div className="container-fluid">
                    <div className="d-flex align-items-center">
                        <button
                            className="btn btn-outline-light border-0 ms-1 me-3"
                            onClick={toggleSidebar}
                        >
                            <i className="bi bi-list"></i>
                        </button>
                        <Link to="/admin" className="navbar-brand text-white fw-bold">
                            Gundam Impact Galaxy
                        </Link>
                    </div>

                    <div className="ms-auto d-flex align-items-center">
                        <div className="dropdown">
                            <button
                                className="btn dropdown-toggle d-flex align-items-center border-0"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                <div className="d-flex align-items-center">
                                    <div className="me-3 text-white">Xin chào Admin</div>
                                    <div className="rounded-circle overflow-hidden" style={{ width: '35px', height: '35px' }}>
                                        <img
                                            src="../assets/default-avatar.png"
                                            alt="Admin Avatar"
                                            className="img-fluid"
                                        />
                                    </div>
                                </div>
                            </button>
                            <ul className="dropdown-menu dropdown-menu-end shadow">
                                <li>
                                    <Link className="dropdown-item d-flex align-items-center" to="/admin/profile">
                                        <i className="bi bi-person me-2"></i> Thông tin tài khoản
                                    </Link>
                                </li>
                                <li>
                                    <Link className="dropdown-item d-flex align-items-center" to="/">
                                        <i className="bi bi-house-door me-2"></i> Quay lại trang chủ
                                    </Link>
                                </li>
                                <li><hr className="dropdown-divider" /></li>
                                <li>
                                    <button className="dropdown-item d-flex align-items-center text-danger" onClick={() => console.log('Logout')}>
                                        <i className="bi bi-box-arrow-right me-2"></i> Đăng xuất
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            {/* Main Content with Sidebar */}
            <div className="d-flex flex-grow-1">
                {/* Sidebar */}
                <div
                    className={`sidebar p-3 ${sidebarCollapsed ? 'collapsed' : ''}`}
                >
                    <div>
                        {renderMenuItems(menuSideBar)}
                    </div>
                </div>

                {/* Main Content */}
                <div
                    className={`content-wrapper flex-grow-1 p-4 ${sidebarCollapsed ? 'collapsed' : ''}`}
                >
                    <Outlet />
                </div>
            </div>
        </div>
    );
};

export default AdminLayout;