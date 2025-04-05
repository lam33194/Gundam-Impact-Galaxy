import { Link } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

function Header() {
  const { user, isAuthenticated, logout } = useAuth();

  return (
    <header className="py-3">
      <div className="container">
        <div className="row align-items-center">
          <div className="col-md-3">
            <a style={{ 'textDecoration': 'none' }} href="/"><h1 className="fs-4 fw-bold mb-0">Gundam Impact Galaxy</h1></a>
          </div>

          <div className="col-md-5">
            <div className="input-group">
              <input
                type="text"
                className="form-control"
                placeholder="Tìm kiếm sản phẩm..."
                aria-label="Tìm kiếm"
              />
              <button className="btn btn-outline-secondary" type="button">
                <i className="fas fa-search me-1"></i> Tìm kiếm
              </button>
            </div>
          </div>

          <div className="col-md-4">
            <div className="d-flex justify-content-end align-items-center">
              {isAuthenticated ? (
                <div className="dropdown me-4">
                  <button
                    className="btn dropdown-toggle d-flex align-items-center"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                  >
                    <span className="me-2">Xin chào, {user?.name}</span>
                  </button>
                  <ul className="dropdown-menu dropdown-menu-end">
                    <li>
                      <Link className="dropdown-item" to="/profile">
                        <i className="fas fa-user me-2"></i>
                        Thông tin người dùng
                      </Link>
                    </li>
                    <li>
                      <Link className="dropdown-item" to="/orders">
                        <i className="fas fa-shopping-bag me-2"></i>
                        Lịch sử mua hàng
                      </Link>
                    </li>
                    <li><hr className="dropdown-divider" /></li>
                    <li>
                      <button
                        className="dropdown-item text-danger"
                        onClick={logout}
                      >
                        <i className="fas fa-sign-out-alt me-2"></i>
                        Đăng xuất
                      </button>
                    </li>
                  </ul>
                </div>
              ) : (
                <div className="me-4">
                  <Link to="/login" className="text-decoration-none me-3">
                    Đăng nhập
                  </Link>
                  <Link to="/signup" className="text-decoration-none">
                    Đăng ký
                  </Link>
                </div>
              )}
              <Link
                to="/cart"
                className="text-decoration-none d-flex align-items-center"
              >
                <i className="fas fa-shopping-cart me-1"></i>
                <span>Giỏ hàng</span>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </header>
  );
}

export default Header;
