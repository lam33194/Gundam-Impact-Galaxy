import "./Cart.scss";
function Cart() {
    return (
        <div className="checkout-page container">
             <div className="nav d-flex align-items-center">
                <a href="" className="text-decoration-none text-dark">
                    Trang chủ
                </a>
                <span className="mx-2">/</span>
                <span className="text-muted">
                    Giỏ hàng
                </span>
            </div>
            <h4 className="text-uppercase my-4 fw-bold">Giỏ hàng của bạn</h4>
            <div className="all-tables">
                <table className="table table-responsive table-bordered">
                    <thead className="align-middle">
                        <tr>
                            <th scope="col" className="w-40">
                                Thông tin sản phẩm
                            </th>
                            <th scope="col" className="w-20">
                                Đơn giá
                            </th>
                            <th scope="col" className="w-20">
                                Số lượng
                            </th>
                            <th scope="col" className="w-20">
                                Thành tiền
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td className="w-40">
                                <div className="d-flex gap-3">
                                    <div
                                        className="img-product"
                                        style={{
                                            backgroundImage: `url(https://bizweb.dktcdn.net/thumb/compact/100/456/060/products/888bf7e8-1bc4-4fba-90fa-4afa82b6d6dc-1741974435553.jpg?v=1741974439350)`,
                                        }}
                                    ></div>

                                    <div className="info d-flex flex-column justify-content-center">
                                        <span>
                                            Mô hình Dragon Girl Loong - Chính
                                            hãng Cangtoys
                                        </span>
                                        <a href="#">Xóa</a>
                                    </div>
                                </div>
                            </td>

                            <td className=" align-middle w-20">
                                <strong className="price">200,000đ</strong>
                            </td>

                            <td className=" align-middle w-20">
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
                            </td>

                            <td className=" align-middle w-20">
                                <strong className="price">400,000đ</strong>
                            </td>
                        </tr>

                        <tr>
                            <td className="w-40">
                                <div className="d-flex gap-3">
                                    <div
                                        className="img-product"
                                        style={{
                                            backgroundImage: `url(https://bizweb.dktcdn.net/thumb/compact/100/456/060/products/888bf7e8-1bc4-4fba-90fa-4afa82b6d6dc-1741974435553.jpg?v=1741974439350)`,
                                        }}
                                    ></div>

                                    <div className="info d-flex flex-column justify-content-center">
                                        <span>
                                            Mô hình Dragon Girl Loong - Chính
                                            hãng Cangtoys
                                        </span>
                                        <a href="#">Xóa</a>
                                    </div>
                                </div>
                            </td>

                            <td className=" align-middle w-20">
                                <strong className="price">200,000đ</strong>
                            </td>

                            <td className=" align-middle w-20">
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
                            </td>

                            <td className=" align-middle w-20">
                                <strong className="price">400,000đ</strong>
                            </td>
                        </tr>

                        <tr>
                            <td className="w-40">
                                <div className="d-flex gap-3">
                                    <div
                                        className="img-product"
                                        style={{
                                            backgroundImage: `url(https://bizweb.dktcdn.net/thumb/compact/100/456/060/products/888bf7e8-1bc4-4fba-90fa-4afa82b6d6dc-1741974435553.jpg?v=1741974439350)`,
                                        }}
                                    ></div>

                                    <div className="info d-flex flex-column justify-content-center">
                                        <span>
                                            Mô hình Dragon Girl Loong - Chính
                                            hãng Cangtoys
                                        </span>
                                        <a href="#">Xóa</a>
                                    </div>
                                </div>
                            </td>

                            <td className=" align-middle w-20">
                                <strong className="price">200,000đ</strong>
                            </td>

                            <td className=" align-middle w-20">
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
                            </td>

                            <td className=" align-middle w-20">
                                <strong className="price">400,000đ</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table className="table table-responsive" style={{ height: '100px' }}>
                    <tbody>
                        <tr>
                            <td>Tổng tiền:</td>
                            <td><strong className="price">200,000đ</strong></td>
                        </tr>
                        <tr>
                            <td colSpan={2} className="">
                                <a href="#" className="btn btn-dark col-12">
                                    Thanh toán
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    );
}

export default Cart;
