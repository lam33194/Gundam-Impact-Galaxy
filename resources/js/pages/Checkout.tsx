import React, { useEffect, useState } from "react";
import "./Checkout.scss";
import "bootstrap/dist/css/bootstrap.min.css";
import { getCart } from "../services/CartService";
import { FormatCurrency } from "../utils/FormatCurrency";
import { addOrder } from "../services/OrderService";
import { toast } from "react-toastify";
import { useNavigate } from "react-router-dom";

const Checkout = () => {
    const [cart, setCart] = useState([]);
    const [total, setTotal] = useState(0);
    const nav = useNavigate();

    const [formData, setFormData] = useState({
        user_name: "",
        user_email: "",
        user_phone: "",
        user_address: "",
        province: "",
        district: "",
        ward: "",
        user_note: "",
        type_payment: "",
    });

    const handleChange = (e: any) => {
        const { name, value } = e.target;
        setFormData({
            ...formData,
            [name]: value,
        });
    };

    const createOrder = async () => {
        const { user_address, province, district, ward } = formData;
        const fullAddress = `${user_address}, ${ward}, ${district}, ${province}`;

        console.log("Địa chỉ đầy đủ:", fullAddress);
        console.log(formData);
        const data = {
            user_name: formData.user_name,
            user_email: formData.user_email,
            user_phone: formData.user_phone,
            user_address: fullAddress,
            user_note: formData.user_note || "",
            type_payment: formData.type_payment,
        };
        try {
            const res = await addOrder(data);
            if (res && res.data) {
                console.log('Đặt hàng thành công:', res.data);
                toast.success("Đặt hàng thành công!");
                setTimeout(() => {
                    window.location.href = "/order-history";
                }, 1000);
            }
        } catch (error) {
            console.error('Lỗi xảy ra:', error);
            if (error.response && error.response.data && error.response.data.message) {
                console.log('Thông báo lỗi:', error.response.data.message);
                toast.error(error.response.data.message);
            } else {
                toast.error("Có lỗi xảy ra, vui lòng thử lại!");
            }
        }
        
    };
    const getCartDetail = async () => {
        try {
            const res = await getCart();
            if (res && res.data) {
                console.log("iudie", res.data.data);
                setCart(res.data.data);
                let t = 0;
                res.data.data.forEach((element: any) => {
                    t +=
                        Number(element.variant.product.price_sale) *
                        Number(element.quantity);
                });
                console.log(t);
                setTotal(t);
            }
        } catch (error) {}
    };

    useEffect(() => {
        getCartDetail();
    }, []);

    return (
        <div className="checkout container d-flex gap-5">
            <div className="col-8">
                <div className="row">
                    <div className="col-12 text-center mb-4">
                        <h4>Tiền hành đặt hàng</h4>
                    </div>

                    <div className="col-md-6">
                        <h5>Thông tin nhận hàng</h5>
                        <div className="mb-3">
                            <div className="form-floating mb-3">
                                <input
                                    type="text"
                                    className="form-control"
                                    name="user_name"
                                    placeholder="Họ và tên"
                                    value={formData.user_name}
                                    onChange={handleChange}
                                />
                                <label htmlFor="floatingFullName">
                                    Họ và tên
                                </label>
                            </div>

                            <div className="form-floating mb-3">
                                <input
                                    type="email"
                                    className="form-control"
                                    name="user_email"
                                    placeholder="Email"
                                    value={formData.user_email}
                                    onChange={handleChange}
                                />
                                <label htmlFor="floatingEmail">Email</label>
                            </div>

                            <div className="form-floating mb-3">
                                <input
                                    type="text"
                                    className="form-control"
                                    name="user_phone"
                                    placeholder="Số điện thoại"
                                    value={formData.user_phone}
                                    onChange={handleChange}
                                />
                                <label htmlFor="floatingPhone">
                                    Số điện thoại
                                </label>
                            </div>

                            <div className="form-floating mb-3">
                                <input
                                    type="text"
                                    className="form-control"
                                    name="user_address"
                                    placeholder="Địa chỉ"
                                    value={formData.user_address}
                                    onChange={handleChange}
                                />
                                <label htmlFor="floatingAddress">Địa chỉ</label>
                            </div>

                            <div className="form-floating mb-3">
                                <input
                                    type="text"
                                    className="form-control"
                                    name="province"
                                    placeholder="Chọn Tỉnh thành"
                                    value={formData.province}
                                    onChange={handleChange}
                                    required
                                />
                                <label htmlFor="floatingProvince">
                                    Tỉnh thành
                                </label>
                            </div>

                            <div className="form-floating mb-3">
                                <input
                                    type="text"
                                    className="form-control"
                                    name="district"
                                    placeholder="Chọn Quận huyện"
                                    value={formData.district}
                                    onChange={handleChange}
                                    required
                                />
                                <label htmlFor="floatingDistrict">
                                    Quận huyện
                                </label>
                            </div>

                            <div className="form-floating mb-3">
                                <input
                                    type="text"
                                    className="form-control"
                                    name="ward"
                                    placeholder="Chọn Phường xã"
                                    value={formData.ward}
                                    onChange={handleChange}
                                    required
                                />
                                <label htmlFor="floatingWard">Phường xã</label>
                            </div>

                            <div className="form-floating mb-3">
                                <textarea
                                    className="form-control"
                                    name="user_note"
                                    placeholder="Ghi chú (tùy chọn)"
                                    style={{ height: "100px" }}
                                    value={formData.user_note}
                                    onChange={handleChange}
                                ></textarea>
                                <label htmlFor="floatingNote">
                                    Ghi chú (tùy chọn)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div className="col-md-6">
                        <h5>Vận chuyển</h5>
                        <div className="d-flex justify-content-between align-items-center border p-2 mb-3">
                            <span>Vận chuyển tiêu chuẩn</span>
                            <span>0₫</span>
                        </div>

                        <h5>Thanh toán</h5>
                        <div className="form-check mb-2">
                            <input
                                type="radio"
                                name="type_payment"
                                id="codPaymentMethod"
                                className="form-check-input"
                                checked={formData.type_payment === "cod"}
                                value="cod"
                                onChange={handleChange}
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
                                name="type_payment"
                                id="momoPaymentMethod"
                                className="form-check-input"
                                value={formData.type_payment}
                                onChange={handleChange}
                            />
                            <label
                                htmlFor="momoPaymentMethod"
                                className="form-check-label d-flex align-items-center ms-2"
                            >
                                Momo
                            </label>
                        </div>

                        <div className="form-check mb-2 d-flex align-items-center">
                            <input
                                type="radio"
                                name="type_payment"
                                id="vnpayPaymentMethod"
                                className="form-check-input"
                                value={formData.type_payment}
                                onChange={handleChange}
                            />
                            <label
                                htmlFor="vnpayPaymentMethod"
                                className="form-check-label d-flex align-items-center ms-2"
                            >
                                Vnpay
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div className="cart-info col-4">
                <h4 className="title">Đơn hàng ({cart.length} sản phẩm)</h4>
                <div className="line"></div>
                <div className="cart-product d-flex flex-column gap-4 my-3">
                    {cart.map((item, index) => (
                        <div
                            key={index}
                            className="cart-product-item d-flex align-items-center justify-content-between"
                        >
                            <div
                                className="cart-product-item-image col-2"
                                style={{
                                    backgroundImage: `url(${
                                        item.image ||
                                        "https://bizweb.dktcdn.net/thumb/medium/100/456/060/products/f84eb124-0644-448c-8e8c-30776876301d-1735131922675.jpg?v=1735134125243"
                                    })`,
                                    height: "60px",
                                    backgroundSize: "cover",
                                    backgroundPosition: "center",
                                }}
                            ></div>
                            <span className="item-name d-block fw-bold col-5">
                                {item.variant.product.name ||
                                    "Tên sản phẩm không xác định"}
                            </span>
                            <span className="item-quantity d-block fw-bold col-2">
                                x{item.quantity}
                            </span>
                            <span className="item-price text-muted col-2">
                                {FormatCurrency(
                                    item.variant.product.price_sale
                                )}
                                đ
                            </span>
                        </div>
                    ))}
                </div>

                {/* <div className="cart-product-item d-flex align-items-center justify-content-between">
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
                </div> */}
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
                    <span>{FormatCurrency(total)}đ</span>
                </div>
                <div className="shipping-fee d-flex justify-content-between align-items-center">
                    <span>Vận chuyển</span>
                    <span>0đ</span>
                </div>
                <div className="line"></div>
                <div className="total d-flex justify-content-between align-items-center">
                    <span>Tổng cộng</span>
                    <span>{FormatCurrency(total)}đ</span>
                </div>
                <div className="order justify-content-end d-flex mt-3">
                    <button
                        className="btn btn-success me-0"
                        onClick={createOrder}
                    >
                        Đặt hàng
                    </button>
                </div>
            </div>
        </div>
    );
};

export default Checkout;
