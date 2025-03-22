

function Header() {
  return (
      <header className="py-3">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-md-3">
              <a style={{'textDecoration' : 'none'}} href="/"><h1 className="fs-4 fw-bold mb-0">Gundam Impact Galaxy</h1></a>
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
                <div className="me-4">
                  <a href="/login" className="text-decoration-none me-3">
                    Đăng nhập
                  </a>
                  <a href="/signup" className="text-decoration-none">
                    Đăng ký
                  </a>
                </div>
                <a
                  href="/cart"
                  className="text-decoration-none d-flex align-items-center"
                >
                  <i className="fas fa-shopping-cart me-1"></i>
                  <span>Giỏ hàng</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </header>

  );
}

export default Header;
