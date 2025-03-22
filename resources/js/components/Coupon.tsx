import "./Coupon.scss";

function Coupon() {
  return (
    <div className="coupon d-flex gap-3 align-items-center">
      <div className="image p-2">
        <img
          width="70"
          height="70"
          src="//bizweb.dktcdn.net/100/456/060/themes/962001/assets/hotcoupon.jpg?1740630578329"
          alt="5%"
        />
        <span>5%</span>
      </div>
      <div className="content_wrap py-2 px-1 gap-2 d-flex flex-column ">
        <div className="content-top d-flex flex-column">
          <span className="fw-bold">NHẬP MÃ: HKD520</span>
          <span>Giảm ngay 5%</span>
        </div>
        <div className="content-bottom d-flex align-items-center justify-content-between">
          <div
            className="coupon-code btn-dark btn btn-sm"
            data-copy="HKD520"
            title="Sao chép"
          >
            Sao chép mã
          </div>

          <a
            title="Chi tiết"
            href="javascript:;"
            className="info-button"
            data-coupon="HKD520"
            data-content="<h4>Mã giảm giá: HKD520</h4>
<h4>Hạn sử dụng mã: 1/1/2024 - 1/1/2025</h4>
<div >Giảm 5% tối đa 20k cho đơn từ 200k. Lượt sử dụng có hạn. Nhanh tay kẻo lỡ bạn nhé!</div>"
          >
            Điều kiện
          </a>
        </div>
      </div>
    </div>
  );
}

export default Coupon;
