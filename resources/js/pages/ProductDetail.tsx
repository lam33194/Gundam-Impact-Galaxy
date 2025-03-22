import Blog from "../components/Blog";
import "./ProductDetail.scss";

function ProductDetail() {
    return (
        <div className="product-detail container d-flex">
            <div className="detail row col-9 gap-4">
                <div
                    className="img col-lg-5 col-sm-12"
                    style={{
                        backgroundImage:
                            "url(https://bizweb.dktcdn.net/thumb/large/100/456/060/products/3fce7911-d633-4417-b263-a30ba273c623.jpg?v=1732162521390)",
                    }}
                ></div>

                <div className="info d-flex flex-column gap-1 col-lg-6 col-sm-12">
                    <h5 className="fw-bold mb-0">
                        Mô hình tàu One Piece (15cm) - Baratie (Sanji) - Mô hình
                        chính hãng Bandai Nhật Bản
                    </h5>
                    <div className="d-flex gap-4">
                        <span>
                            Thương hiệu: <strong>BANDAI</strong>
                        </span>
                        <span>Mã sản phẩm: 4543112913982 </span>
                    </div>
                    <span className="price">380.000đ</span>
                    <div className="line mb-2 mt-1"></div>
                    <span>5 Mã Giảm Giá</span>
                    <div className="coupon-lists d-flex gap-2">
                        <div className="coupon py-1 px-2 col-2">Giảm 5%</div>
                        <div className="coupon py-1 px-2 col-2">Giảm 5%</div>
                        <div className="coupon py-1 px-2 col-2">Giảm 5%</div>
                        <div className="coupon py-1 px-2 col-2">Giảm 5%</div>
                    </div>
                    <div className="line mb-2 mt-2"></div>
                    <span>Số lượng:</span>
                    <div
                        className="input-group input-group-sm quantity-selector"
                        style={{ width: "100px" }}
                    >
                        <button
                            className="btn btn-outline-dark"
                            type="button"
                            id="button-minus"
                        >
                            <i className="bi bi-dash"></i>
                        </button>

                        <input
                            type="text"
                            className="fw-bold form-control text-center"
                            value="1"
                            aria-label="Quantity"
                            min="1"
                        />

                        <button
                            className="btn btn-outline-dark"
                            type="button"
                            id="button-plus"
                        >
                            <i className="bi bi-plus"></i>
                        </button>
                    </div>

                    <div className="d-flex gap-2 pay my-2">
                        <button className="btn btn-dark col-10 d-flex flex-column">
                            <span>Thanh toán online hoặc ship COD</span>
                            <span className="fw-bold">Mua ngay</span>
                        </button>
                        <button className="btn btn-dark col-2">
                            <i className="fa-solid fa-cart-shopping"></i>
                        </button>
                    </div>

                    <div className="share">
                        <p className="mb-1">Chia sẻ ngay:</p>
                        <button className="me-1 facebook btn btn-sm text-light">
                            <i className="fa-brands fa-facebook-f"></i> Chia sẻ
                        </button>

                        <button className="me-1 pinterest btn btn-sm text-light">
                            <i className="fa-brands fa-pinterest-p"></i> Chia sẻ
                        </button>

                        <button className="twitter btn btn-sm btn-primary text-light">
                            <i className="fa-brands fa-twitter"></i> Chia sẻ
                        </button>
                    </div>
                </div>
            </div>

            <div className="blog-article d-flex flex-column col-3">
                <span className="text-uppercase fw-bold">
                    Ưu đãi thành viên{" "}
                </span>
                <div className="service-info d-flex flex-column mb-4 gap-1">
                    <div className="service py-2 d-flex gap-3 align-items-center">
                        <img src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/ico_sv1.png?1740630578329" />
                        <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                    </div>

                    <div className="service py-2 d-flex gap-3 align-items-center">
                        <img src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/ico_sv2.png?1740630578329" />
                        <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                    </div>

                    <div className="service py-2 d-flex gap-3 align-items-center">
                        <img src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/ico_sv3.png?1740630578329" />
                        <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                    </div>

                    <div className="service py-2 d-flex gap-3 align-items-center">
                        <img src="https://bizweb.dktcdn.net/100/456/060/themes/962001/assets/ico_sv4.png?1740630578329" />
                        <span className="fw-bold">Dịch vụ đóng gói riêng</span>
                    </div>
                </div>

                <span className="fw-bold text-uppercase mb-1">
                    Tin mới nhất
                </span>
                <div className="blog-list d-flex flex-column gap-4">
                    <Blog display={"column"} backgroundSize={"contain"} />
                    <Blog display={"column"} backgroundSize={"contain"} />
                    <Blog display={"column"} backgroundSize={"contain"} />
                </div>
            </div>
        </div>
    );
}

export default ProductDetail;
