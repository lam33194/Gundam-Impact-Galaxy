import { useEffect, useState } from "react";
import { getOrders, getOrderPayment } from "../services/OrderService";
import { STORAGE_URL } from "../utils/constants";
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';
import vnpayLogo from '../assets/vnpay.svg';

const OrderHistory = () => {
    const [orders, setOrders] = useState<any[]>([]);
    const navigate = useNavigate();
    const image = "https://bizweb.dktcdn.net/thumb/large/100/456/060/products/3fce7911-d633-4417-b263-a30ba273c623.jpg?v=1732162521390";

    const getAllHistory = async () => {
        try {
            const res = await getOrders();
            if (res && res.data) {
                setOrders(res.data.data);
                console.log(res.data);
            }
        } catch (error) {
            console.log(error);
        }
    };

    const handlePayment = async (orderId: number) => {
        try {
            const res = await getOrderPayment(orderId);
            if (res?.data?.status === 'success' && res.data.data) {
                window.location.href = res.data.data;
            }
        } catch (error: any) {
            console.error('Payment error:', error);
            toast.error('Có lỗi xảy ra. Vui lòng thử lại sau!');
        }
    };

    const getStatusClass = (status: string) => {
        switch (status) {
            case 'paid':
                return 'bg-success';
            case 'unpaid':
                return 'bg-warning';
            case 'failed':
                return 'bg-danger';
            default:
                return 'bg-warning';
        }
    };

    useEffect(() => {
        getAllHistory();
    }, []);
    return (
        <div className="container mt-5">
            <div className="nav d-flex align-items-center mb-2 ">
                <a href="" className="text-decoration-none text-dark fw-bold">
                    Trang chủ
                </a>
                <span className="mx-2">/</span>
                <span className="text-muted fw-medium">Lịch sử đơn hàng</span>
            </div>
            <h3 className="text-center mb-4">Lịch Sử Đơn Hàng</h3>
            <div className="row">
                {orders.length === 0 ? (
                    <div className="col-12 text-center">
                        <p className="text-muted">Bạn chưa có đơn hàng nào.</p>
                    </div>
                ) : (
                    orders.map((order) => (
                        <div className="col-md-12 mb-4" key={order.id}>
                            <div className="card shadow-sm border-primary">
                                <div className="card-body position-relative">
                                    {order.type_payment === 'vnpay' &&
                                        order.status_order === 'pending' &&
                                        order.status_payment === 'unpaid' && (
                                            <button
                                                className="btn btn-outline-dark btn-sm position-absolute top-0 end-0 m-3 d-flex align-items-center gap-2"
                                                onClick={() => handlePayment(order.id)}
                                            >
                                                <i className="fas fa-credit-card"></i>
                                                <span>Thanh toán</span>
                                            </button>
                                        )}
                                    <h5 className="card-title">
                                        Đơn hàng #{order.order_sku}
                                    </h5>
                                    <p className="card-text">
                                        <strong>Người đặt:</strong>{" "}
                                        {order.user_name}
                                        <br />
                                        <strong>Email:</strong>{" "}
                                        {order.user_email}
                                        <br />
                                        <strong>Số điện thoại:</strong>{" "}
                                        {order.user_phone}
                                        <br />
                                        <strong>Địa chỉ:</strong>{" "}
                                        {order.user_address}
                                        <br />
                                        <strong>Tổng giá:</strong>{" "}
                                        {order.total_price.toLocaleString()} VND
                                        <br />
                                        <strong>
                                            Trạng thái đơn hàng:
                                        </strong>{" "}
                                        <div className="d-inline-flex gap-2 align-items-center">
                                            <span className={`badge ${getStatusClass(order.status_order)}`}>
                                                {order.status_order}
                                            </span>
                                            {order.type_payment === 'vnpay' && (
                                                <span className={`badge ${getStatusClass(order.status_payment)}`}>
                                                    {order.status_payment}
                                                </span>
                                            )}
                                        </div>
                                        <br />
                                        <strong>
                                            Phương thức thanh toán:
                                        </strong>{" "}
                                        {order.type_payment === 'vnpay' ? (
                                            <span className="d-inline-block" style={{ width: '80px', verticalAlign: 'middle' }}>
                                                <img
                                                    src={vnpayLogo}
                                                    alt="VNPay"
                                                    style={{ width: '100%', height: 'auto' }}
                                                />
                                            </span>
                                        ) : (
                                            order.type_payment
                                        )}
                                    </p>

                                    {/* Bảng sản phẩm trong đơn hàng */}
                                    <h6 className="mt-4">Sản phẩm:</h6>
                                    <table className="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Ảnh</th>
                                                <th>Tên sản phẩm</th>
                                                <th>Số lượng</th>
                                                <th>Màu</th>
                                                <th>Kích thước</th>
                                                <th>Giá</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {order.order_items.map((item) => {
                                                const item_price = item.product_price_sale != 0 ? item.product_price_sale : item.product_price_regular;
                                                return (
                                                    <tr key={item.id}>
                                                        <td>
                                                            <img
                                                                src={
                                                                    // item.product_img_thumbnail? image : ''
                                                                    STORAGE_URL + item.product_img_thumbnail
                                                                }
                                                                alt={
                                                                    item.product_name
                                                                }
                                                                style={{
                                                                    width: "50px",
                                                                    height: "auto",
                                                                    borderRadius:
                                                                        "4px",
                                                                }}
                                                            />
                                                        </td>
                                                        <td>{item.product_name}</td>
                                                        <td>{item.quantity}</td>
                                                        <td>{item.variant_color_name}</td>
                                                        <td>{item.variant_size_name}</td>
                                                        <td>
                                                            {(
                                                                item_price *
                                                                item.quantity
                                                            ).toLocaleString()}{" "}
                                                            đ
                                                        </td>
                                                    </tr>
                                                )
                                            })}
                                        </tbody>
                                    </table>

                                    {/* <button
                                        className="btn btn-primary"
                                        onClick={() =>
                                            redirectToDetail(order.slug)
                                        }
                                    >
                                        Xem Chi Tiết
                                    </button> */}
                                </div>
                            </div>
                        </div>
                    ))
                )}
            </div>
        </div>
    );
};


export default OrderHistory;
