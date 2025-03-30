import React from "react";
import "./Checkout.scss";
import "bootstrap/dist/css/bootstrap.min.css";

const Checkout = () => {
    return (
        <div className="checkout container d-flex gap-5">
            <div className="col-8">
                <div className="row">
                    <div className="col-12 text-center mb-4">
                        <h1>HAKUDA</h1>
                    </div>

                    <div className="col-md-6">
                        <h5>Thông tin nhận hàng</h5>
                        <form>
                            <div className="form-floating mb-3">
                                <input
                                    type="email"
                                    className="form-control"
                                    id="floatingEmail"
                                    placeholder="Email"
                                />
                                <label htmlFor="floatingEmail">Email</label>
                            </div>

                            <div className="form-floating mb-3">
                                <input
                                    type="text"
                                    className="form-control"
                                    id="floatingFullName"
                                    placeholder="Họ và tên"
                                />
                                <label htmlFor="floatingFullName">
                                    Họ và tên
                                </label>
                            </div>

                            <div className="form-floating mb-3">
                                <input
                                    type="text"
                                    className="form-control"
                                    id="floatingPhone"
                                    placeholder="Số điện thoại"
                                />
                                <label htmlFor="floatingPhone">
                                    Số điện thoại
                                </label>
                            </div>

                            <div className="form-floating mb-3">
                                <input
                                    type="text"
                                    className="form-control"
                                    id="floatingAddress"
                                    placeholder="Địa chỉ"
                                />
                                <label htmlFor="floatingAddress">Địa chỉ</label>
                            </div>

                            <div className="form-floating mb-3">
                                <select
                                    className="form-select"
                                    id="floatingProvince"
                                    aria-label="Tỉnh thành"
                                >
                                    <option value="">Hà Nội</option>
                                </select>
                                <label htmlFor="floatingProvince">
                                    Tỉnh thành
                                </label>
                            </div>

                            <div className="form-floating mb-3">
                                <select
                                    className="form-select"
                                    id="floatingDistrict"
                                    aria-label="Quận huyện"
                                >
                                    <option value="">Quận Cầu Giấy</option>
                                </select>
                                <label htmlFor="floatingDistrict">
                                    Quận huyện
                                </label>
                            </div>

                            <div className="form-floating mb-3">
                                <select
                                    className="form-select"
                                    id="floatingWard"
                                    aria-label="Phường xã"
                                >
                                    <option value="">Phường Mai Dịch</option>
                                </select>
                                <label htmlFor="floatingWard">Phường xã</label>
                            </div>

                            <div className="form-floating mb-3">
                                <textarea
                                    className="form-control"
                                    id="floatingNote"
                                    placeholder="Ghi chú (tùy chọn)"
                                    style={{ height: "100px" }}
                                ></textarea>
                                <label htmlFor="floatingNote">
                                    Ghi chú (tùy chọn)
                                </label>
                            </div>
                        </form>
                    </div>

                    <div className="col-md-6">
                        <h5>Vận chuyển</h5>
                        <div className="d-flex justify-content-between align-items-center border p-2 mb-3">
                            <span>Vận chuyển tiêu chuẩn</span>
                            <span>25.000₫</span>
                        </div>

                        <h5>Thanh toán</h5>
                        <div className="form-check mb-2">
                            <input
                                type="radio"
                                name="paymentMethod"
                                id="codPaymentMethod"
                                className="form-check-input"
                                defaultChecked
                            />
                            <label
                                htmlFor="codPaymentMethod"
                                className="form-check-label"
                            >
                                Thanh toán khi giao hàng (COD)
                            </label>
                        </div>

                        <div className="form-check mb-2 d-flex align-items-center">
                            <input
                                type="radio"
                                name="paymentMethod"
                                id="bankTransferPaymentMethod"
                                className="form-check-input"
                            />
                            <label
                                htmlFor="bankTransferPaymentMethod"
                                className="form-check-label d-flex align-items-center ms-2"
                            >
                                Chuyển khoản ngân hàng
                                <img
                                    src="/vietqr.png"
                                    alt=""
                                    style={{
                                        height: "20px",
                                        marginLeft: "10px",
                                    }}
                                />
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div className="cart-info col-4">
                <h4 className="title">Đơn hàng (2 sản phẩm)</h4>
                <div className="line"></div>
                <div className="cart-product d-flex flex-column gap-4 my-3">
                    <div className="cart-product-item d-flex align-items-center justify-content-between">
                        <div
                            className="cart-product-item-image col-2"
                            style={{
                                backgroundImage:
                                    "url(https://bizweb.dktcdn.net/thumb/medium/100/456/060/products/f84eb124-0644-448c-8e8c-30776876301d-1735131922675.jpg?v=1735134125243)",
                                height: "60px",
                                backgroundSize: "cover",
                                backgroundPosition: "center",
                             
                            }}
                        ></div>
                        <span className="item-name d-block fw-bold col-7">
                            Mô hình tàu One Piece (15cm) - Marine Warship (Tàu
                            Hải quân) - Mô hình chính hãng Bandai Nhật Bản
                        </span>
                        <span className="item-price text-muted col-2">
                            1.000.000đ
                        </span>
                    </div>

                    <div className="cart-product-item d-flex align-items-center justify-content-between">
                        <div
                            className="cart-product-item-image col-2"
                            style={{
                                backgroundImage:
                                    "url(https://bizweb.dktcdn.net/thumb/medium/100/456/060/products/f84eb124-0644-448c-8e8c-30776876301d-1735131922675.jpg?v=1735134125243)",
                                height: "60px",
                                backgroundSize: "cover",
                                backgroundPosition: "center",
                             
                            }}
                        ></div>
                        <span className="item-name d-block fw-bold col-7">
                            Mô hình tàu One Piece (15cm) - Marine Warship (Tàu
                            Hải quân) - Mô hình chính hãng Bandai Nhật Bản
                        </span>
                        <span className="item-price text-muted col-2">
                            1.000.000đ
                        </span>
                    </div>
                </div>
                <div className="enter-coupon d-flex gap-1">
                    <div className="form-floating col-8">
                        <input
                            className="form-control"
                            id="floatingNote"
                            placeholder="Nhập mã giảm giá"
                        ></input>
                        <label htmlFor="floatingNote">Nhập mã giảm giá</label>
                    </div>
                    <button className="btn btn-primary col-4">Áp dụng</button>
                </div>

                <div className="line"></div>
                <div className="temporary-price d-flex justify-content-between align-items-center">
                    <span>Tạm thời</span>
                    <span>700.000đ</span>
                </div>
                <div className="shipping-fee d-flex justify-content-between align-items-center">
                    <span>Vận chuyển</span>
                    <span>20.000đ</span>
                </div>
                <div className="line"></div>
                <div className="total d-flex justify-content-between align-items-center">
                    <span>Tổng cộng</span>
                    <span>720.000đ</span>
                </div>
                <div className="order justify-content-end d-flex mt-3">
                <button className="btn btn-success me-0">Đặt hàng</button>
                </div>
            </div>
        </div>
    );
};

export default Checkout;
