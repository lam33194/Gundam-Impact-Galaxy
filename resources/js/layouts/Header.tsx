import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { useCart } from '../context/CartContext';
import './Header.scss';
import { useState, useEffect } from 'react';
import ConfirmModal from '../components/ConfirmModal';

function Header() {
  const { user, isAuthenticated, logout } = useAuth();
  const { cartItemCount } = useCart();
  const navigate = useNavigate();
  const [searchKeyword, setSearchKeyword] = useState('');
  const [isDropdownOpen, setIsDropdownOpen] = useState(false);
  const [showLogoutModal, setShowLogoutModal] = useState(false);

  const handleSearch = () => {
    if (!searchKeyword.trim()) {
      navigate('/search');
    } else {
      navigate('/search', {
        state: {
          initialSearchType: 'name',
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
  };

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (isDropdownOpen && !(event.target as HTMLElement).closest('.dropdown')) {
        setIsDropdownOpen(false);
      }
    };

    document.addEventListener('click', handleClickOutside);
    return () => document.removeEventListener('click', handleClickOutside);
  }, [isDropdownOpen]);

  return (
    <>
      <header className="py-3">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-md-3">
              <Link to="/" className="header-logo text-decoration-none">
                <img
                  src="/logo.svg"
                  alt="Gundam Impact Galaxy Logo"
                />
                <h2 className="fw-bold text-dark">Gundam Impact Galaxy</h2>
              </Link>
            </div>

            <div className="col-md-5">
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
              <div className="d-flex justify-content-end align-items-center">
                {isAuthenticated ? (
                  <>
                    <div className="dropdown me-4">
                      <button
                        className={`btn dropdown-toggle d-flex align-items-center ${isDropdownOpen ? 'show' : ''}`}
                        type="button"
                        onClick={() => setIsDropdownOpen(!isDropdownOpen)}
                      >
                        <span className="me-2">Xin chào, {user?.name}</span>
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
                      className="btn btn-outline-dark d-flex align-items-center gap-2 position-relative"
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
                  <div className="auth-buttons d-flex align-items-center gap-3">
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
