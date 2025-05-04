import React from 'react';

function Footer() {
  return (
    <div className="bg-white d-flex flex-column">
      <div className="border border-secondary-subtle py-3">
        <div className="d-flex justify-content-center align-items-center gap-3">
          <span className="fw-semibold">Thắc mắc vui lòng liên hệ</span>
          <a
            href="https://zalo.me/g/qybypk440"
            className="d-flex justify-content-center align-items-center bg-light rounded-circle p-2 text-dark"
            style={{ width: "45px", height: "45px" }}
          >
            <img
    src="https://e7.pngegg.com/pngimages/851/579/png-clipart-zalo-laptop-login-mobile-phones-qr-code-laptop-electronics-text.png"
    alt="Zalo"
    style={{ width: "50px", height: "50px", objectFit: "contain" }}
  />
</a>

        </div>
      </div>


      <div className="bg-light">
        <div className="container">
          <div className="row py-4 g-4">
            <div className="col-lg-3 col-sm-6 d-flex flex-column gap-2">
              <h6 className="fw-bold">Về Gundam Impact Galaxy</h6>
              <span className="text-secondary">
                HỘ KINH DOANH Gundam Impact Galaxy do
                Cao Đẳng FPOLY thành phố Hà Nội. MST: JQK
              </span>
            </div>
            <div className="col-lg-3 col-sm-6 d-flex flex-column gap-2">
              <h6 className="fw-bold">Hướng dẫn</h6>
              <a href="#" className="text-decoration-none text-secondary">Hướng dẫn đặt đơn Pre-Order</a>
              <a href="#" className="text-decoration-none text-secondary">Hướng dẫn mua hàng</a>
              <a href="#" className="text-decoration-none text-secondary">Hướng dẫn thanh toán</a>
              <a href="#" className="text-decoration-none text-secondary">Câu hỏi thường gặp - FAQs</a>
              <a href="#" className="text-decoration-none text-secondary">Trải nghiệm mua sắm 100% hài lòng</a>
              <a href="#" className="text-decoration-none text-secondary">Ưu đãi dành riêng cho hội viên</a>
            </div>
            <div className="col-lg-3 col-sm-6 d-flex flex-column gap-2">
              <h6 className="fw-bold">Chính sách</h6>
              <a href="#" className="text-decoration-none text-secondary">Chính sách chung</a>
              <a href="#" className="text-decoration-none text-secondary">Chính sách bảo hành - đổi trả</a>
              <a href="#" className="text-decoration-none text-secondary">Chính sách vận chuyển</a>
              <a href="#" className="text-decoration-none text-secondary">Chính sách kiểm hàng</a>
              <a href="#" className="text-decoration-none text-secondary">Chính sách bảo mật thông tin</a>
            </div>
            <div className="col-lg-3 col-sm-6 d-flex flex-column gap-2">
              <h6 className="fw-bold">Liên hệ</h6>
              <span className="text-secondary">
                Nếu bạn cần hỗ trợ hoặc có bất kỳ thắc mắc gì, hãy liên hệ ngay
                với Gundam Impact Galaxy nhé!
              </span>
              <span className="text-secondary ms-2">
                <i className="fa-solid fa-location-dot me-2"></i>
                Cao Đẳng FPOLY thành phố Hà Nội
              </span>
              <span className="text-secondary ms-2">
                <i className="fa-solid fa-phone me-2"></i>096 498 3498
              </span>
              <span className="text-secondary ms-2">
                <i className="fa-solid fa-envelope me-2"></i>
                GundamImpactGalaxy@gmail.com
              </span>
            </div>
          </div>
        </div>
      </div>

      <div className="bg-light border-top">
        <div className="container">
          <div className="px-4 py-3 text-center">
            <span className="text-secondary">
              Bản quyền thuộc về Gundam Impact Galaxy Cung cấp bởi FPOLY
            </span>
          </div>
        </div>
      </div>
    </div>
  );
}

export default Footer;
