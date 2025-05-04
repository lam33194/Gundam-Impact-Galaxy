import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { useCart } from '../context/CartContext';
import './Header.scss';
import { useState, useEffect } from 'react';
import ConfirmModal from '../components/ConfirmModal';
import { getAllCategories, getAllTags } from '../services/ProductService';
import { toast } from 'react-toastify';

function Header() {
  const { user, isAuthenticated, logout } = useAuth();
  const { cartItemCount } = useCart();
  const navigate = useNavigate();
  const [searchKeyword, setSearchKeyword] = useState('');
  const [isDropdownOpen, setIsDropdownOpen] = useState(false);
  const [showLogoutModal, setShowLogoutModal] = useState(false);
  const [categories, setCategories] = useState<any[]>([]);
  const [tags, setTags] = useState<any[]>([]);
  const [isCategoryDropdownOpen, setIsCategoryDropdownOpen] = useState(false);

  const handleSearch = () => {
    if (!searchKeyword.trim()) {
      navigate('/search');
    } else {
      navigate('/search', {
        state: {
          initialKeyword: searchKeyword.trim()
        }
      });
    }
    setSearchKeyword('');
  };

  const handleKeyPress = (e: React.KeyboardEvent<HTMLInputElement>) => {
    if (e.key === 'Enter') {
      handleSearch();
    }
  };

  const handleLogout = () => {
    setShowLogoutModal(true);
  };

  const confirmLogout = () => {
    logout();
    setShowLogoutModal(false);
    navigate('/');
    toast.success('Đăng xuất thành công!');
  };

  const formatName = (name: string) => {
    if (name.length > 25) {
      return name.slice(0, 25) + '...';
    }
    return name;
  };

  const fetchCategories = async () => {
    try {
      const res = await getAllCategories();
      if (res.data?.data) {
        setCategories(res.data.data);
      }
    } catch (error) {
      console.error('Failed to fetch categories:', error);
    }
  };

  const fetchTags = async () => {
    try {
      const res = await getAllTags();
      if (res.data?.data) {
        setTags(res.data.data);
      }
    } catch (error) {
      console.error('Failed to fetch tags:', error);
    }
  };

  const navigateToCategory = (categorySlug: string, categoryName: string) => {
    if (window.location.pathname === '/search') {
      // If already on search page, use replace to reset history
      navigate('/search', {
        replace: true,
        state: {
          clearFilters: true,
          selectedCategory: categorySlug,
          categoryName: categoryName
        }
      });
    } else {
      // Normal navigation
      navigate('/search', {
        state: {
          selectedCategory: categorySlug,
          categoryName: categoryName
        }
      });
    }
    setIsCategoryDropdownOpen(false);
  };

  const navigateToTag = (tagId: string, tagName: string) => {
    if (window.location.pathname === '/search') {
      // If already on search page, use replace to reset history
      navigate('/search', {
        replace: true,
        state: {
          clearFilters: true,
          selectedTags: [tagId],
          tagName: tagName
        }
      });
    } else {
      // Normal navigation
      navigate('/search', {
        state: {
          selectedTags: [tagId],
          tagName: tagName
        }
      });
    }
    setIsCategoryDropdownOpen(false);
  };

  useEffect(() => {
    fetchCategories();
    fetchTags();
  }, []);

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (isDropdownOpen && !(event.target as HTMLElement).closest('.dropdown')) {
        setIsDropdownOpen(false);
      }
      if (isCategoryDropdownOpen && !(event.target as HTMLElement).closest('.category-dropdown')) {
        setIsCategoryDropdownOpen(false);
      }
    };

    document.addEventListener('click', handleClickOutside);
    return () => document.removeEventListener('click', handleClickOutside);
  }, [isDropdownOpen, isCategoryDropdownOpen]);

  return (
    <>
      <header className="py-3">
        <div className="container-fluid">
          <div className="row align-items-center">
            <div className="col-md-4 d-flex align-items-center">
              {/* Logo and Category dropdown */}
              <Link to="/" className="header-logo text-decoration-none ms-5">
                <img
                  src="/logo.svg"
                  alt="Gundam Impact Galaxy Logo"
                />
                <h2 className="fw-bold text-dark">Gundam Impact Galaxy</h2>
              </Link>
              <div className="category-dropdown ms-4">
                <button
                  className={`btn btn-outline-dark dropdown-toggle ${isCategoryDropdownOpen ? 'show' : ''}`}
                  onClick={() => setIsCategoryDropdownOpen(!isCategoryDropdownOpen)}
                >
                  Danh mục
                </button>
                <div className={`dropdown-menu ${isCategoryDropdownOpen ? 'show' : ''}`}>
                  <div className="dropdown-submenu">
                    <button className="dropdown-item">
                      Thể loại
                    </button>
                    <div className="submenu">
                      {categories.map(category => (
                        <button
                          key={category.slug}
                          className="dropdown-item"
                          onClick={() => navigateToCategory(category.slug, category.name)}
                        >
                          {category.name}
                        </button>
                      ))}
                    </div>
                  </div>
                  <div className="dropdown-submenu">
                    <button className="dropdown-item">
                      Tags
                    </button>
                    <div className="submenu">
                      {tags.map(tag => (
                        <button
                          key={tag.id}
                          className="dropdown-item"
                          onClick={() => navigateToTag(tag.id.toString(), tag.name)}
                        >
                          {tag.name}
                        </button>
                      ))}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div className="col-md-4">
              {/* Search input */}
              <div className="input-group">
                <input
                  type="text"
                  className="form-control"
                  placeholder="Tìm kiếm sản phẩm..."
                  aria-label="Tìm kiếm"
                  value={searchKeyword}
                  onChange={(e) => setSearchKeyword(e.target.value)}
                  onKeyPress={handleKeyPress}
                />
                <button
                  className="btn btn-outline-secondary"
                  type="button"
                  onClick={handleSearch}
                >
                  <i className="fas fa-search me-1"></i> Tìm kiếm
                </button>
              </div>
            </div>
            <div className="col-md-4">
              {/* Auth buttons and cart */}
              <div className="d-flex justify-content-end align-items-center">
                {isAuthenticated ? (
                  <>
                    <div className="dropdown me-4">
                      <button
                        className={`btn dropdown-toggle d-flex align-items-center ${isDropdownOpen ? 'show' : ''}`}
                        type="button"
                        onClick={() => setIsDropdownOpen(!isDropdownOpen)}
                      >
                        <span className="me-2">Xin chào, {formatName(user?.name!)}</span>
                      </button>
                      <ul className={`dropdown-menu dropdown-menu-end ${isDropdownOpen ? 'show' : ''}`}>
                        <li>
                          <Link className="dropdown-item" to="/profile">
                            <i className="fas fa-user me-2"></i>
                            Thông tin người dùng
                          </Link>
                        </li>
                        <li>
                          <Link className="dropdown-item" to="/change-password">
                            <i className="fas fa-key me-2"></i>
                            Đổi mật khẩu
                          </Link>
                        </li>
                        <li>
                          <Link className="dropdown-item" to="/order-history">
                            <i className="fas fa-shopping-bag me-2"></i>
                            Lịch sử mua hàng
                          </Link>
                        </li>
                        <li><hr className="dropdown-divider" /></li>
                        <li>
                          <button
                            className="dropdown-item text-danger"
                            onClick={handleLogout}
                          >
                            <i className="fas fa-sign-out-alt me-2"></i>
                            Đăng xuất
                          </button>
                        </li>
                      </ul>
                    </div>
                    <Link
                      to="/cart"
                      className="btn btn-outline-dark d-flex align-items-center gap-2 position-relative me-5"
                    >
                      <i className="fas fa-shopping-cart"></i>
                      <span>Giỏ hàng</span>
                      {cartItemCount > 0 && (
                        <span className="position-absolute badge rounded-pill bg-danger cart-counter">
                          {cartItemCount}
                        </span>
                      )}
                    </Link>
                  </>
                ) : (
                  <div className="auth-buttons d-flex align-items-center gap-3 me-5">
                    <Link
                      to="/login"
                      className="btn btn-outline-dark d-flex align-items-center gap-2"
                    >
                      <i className="fas fa-sign-in-alt"></i>
                      <span>Đăng nhập</span>
                    </Link>
                    <Link
                      to="/signup"
                      className="btn btn-dark d-flex align-items-center gap-2"
                    >
                      <i className="fas fa-user-plus"></i>
                      <span>Đăng ký</span>
                    </Link>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      </header>

      <ConfirmModal
        show={showLogoutModal}
        onHide={() => setShowLogoutModal(false)}
        onConfirm={confirmLogout}
        title="Xác nhận đăng xuất"
        message="Bạn có chắc chắn muốn đăng xuất khỏi hệ thống?"
        confirmText="Đăng xuất"
        confirmVariant="danger"
        size="lg"
      />
    </>
  );
}

export default Header;
