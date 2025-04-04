import { useEffect, useState } from "react";
import "./Cart.scss";
import { getCart, updateCart } from "../services/CartService";
import { FormatCurrency } from "../utils/FormatCurrency";
import { toast } from "react-toastify";
const Cart = () => {
    const [cart, setCart] = useState([]);
    const [total, setTotal] = useState(0);
    const getCartDetail = async () => {
        try {
            const res = await getCart();
            if (res && res.data) {
                console.log('iudie', res.data.data)
                setCart(res.data.data);
                let t = 0;
                res.data.data.forEach((element: any) => {
                    t += Number(element.variant.product.price_regular) * Number(element.quantity)
                });
                console.log(t);
                setTotal(t);
            }
        } catch (error) {}
    };

    const updateCartItem = async(item_id: any, quantity: any) => {
        try {
            const res = await updateCart(quantity, item_id);
            if (res && res.data){
                getCartDetail();
                if (quantity === 0){
                    toast.success("Xóa thành công mặt hàng!")
                }else{
                    toast.success("Cập nhật giỏ hàng thành công!")
                }
            }
        } catch (error) {
            
        }
    }

    useEffect(() =>{
        if (cart !== null){
            getCartDetail();
        }
    }, [])
    return (
        <div className="checkout-page container">
            <div className="nav d-flex align-items-center">
                <a href="" className="text-decoration-none text-dark">
                    Trang chủ
                </a>
                <span className="mx-2">/</span>
                <span className="text-muted">Giỏ hàng</span>
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
                        {cart &&
                            cart.map((p, index) => {
                                return (
                                    <tr key={index}>
                                        {" "}
                                        {/* Thêm key */}
                                        <td className="w-40">
                                            <div className="d-flex gap-3">
                                                <div
                                                    className="img-product"
                                                    style={{
                                                        backgroundImage: `url(${
                                                           
                                                            "https://bizweb.dktcdn.net/thumb/compact/100/456/060/products/888bf7e8-1bc4-4fba-90fa-4afa82b6d6dc-1741974435553.jpg?v=1741974439350"
                                                        })`,
                                                    }}
                                                ></div>

                                                <div className="info d-flex flex-column justify-content-center">
                                                    <span>
                                                        {p?.variant?.product?.name ||
                                                            "Mô hình Dragon Girl Loong - Chính hãng Cangtoys"}
                                                    </span>
                                                    <button
                                                        onClick={() =>
                                                            updateCartItem(p.id, 0)
                                                        }
                                                        className="btn btn-link"
                                                    >
                                                        {" "}
                                                        {/* Thay a bằng button */}
                                                        Xóa
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td className="align-middle w-20">
                                            <strong className="price">
                                                {FormatCurrency(p.variant.product.price_regular) || "200,000"}đ
                                            </strong>
                                        </td>
                                        <td className="align-middle w-20">
                                            <div
                                                className="input-group input-group-sm quantity-selector"
                                                style={{ width: "100px" }}
                                            >
                                                <button
                                                    className="btn btn-outline-dark"
                                                    type="button"
                                                    id="button-minus"
                                                    onClick={() =>
                                                        updateCartItem(p.id, p.quantity - 1)
                                                    }
                                                >
                                                    <i className="bi bi-dash"></i>
                                                </button>

                                                <input
                                                    type="text" // Đổi input type thành number
                                                    className="fw-bold form-control text-center"
                                                    value={p.quantity || 1} // Sử dụng state cho value
                                                    aria-label="Quantity"
                                                    min="1"
                                                    readOnly
                                                />

                                                <button
                                                    className="btn btn-outline-dark"
                                                    type="button"
                                                    id="button-plus"
                                                    onClick={() =>
                                                        updateCartItem(p.id, p.quantity + 1)
                                                    }
                                                >
                                                    <i className="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td className="align-middle w-20">
                                            <strong className="price">
                                                {FormatCurrency(p.variant.product.price_regular * p.quantity) ||
                                                    "400,000"}đ
                                            </strong>{" "}
                                        </td>
                                    </tr>
                                );
                            })}

                        {/* <tr>
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
                        </tr> */}
                    </tbody>
                </table>

                <table
                    className="table table-responsive"
                    style={{ height: "100px" }}
                >
                    <tbody>
                        <tr>
                            <td>Tổng tiền:</td>
                            <td>
                                <strong className="price">{FormatCurrency(total) || '200,000'}đ</strong>
                            </td>
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
};

export default Cart;
