import { useEffect, useState, useCallback } from "react";
import "./Cart.scss";
import { getCart, updateCart } from "../services/CartService";
import { FormatCurrency } from "../utils/FormatCurrency";
import { toast } from "react-toastify";
import useDebounce from "../hooks/useDebounce";

const Cart = () => {
    const [cart, setCart] = useState([]);
    const [total, setTotal] = useState(0);
    const [isUpdating, setIsUpdating] = useState(false);
    const [pendingUpdates, setPendingUpdates] = useState<Record<number, number>>({});
    const [originalQuantities, setOriginalQuantities] = useState<Record<number, number>>({});

    const debouncedUpdates = useDebounce(pendingUpdates, 1500);

    const getCartDetail = async () => {
        try {
            const res = await getCart();
            if (res && res.data) {
                setCart(res.data.data);
                let t = 0;
                const originalQtys: Record<number, number> = {};
                res.data.data.forEach((element: any) => {
                    t += Number(element.variant.product.price_sale) * Number(element.quantity);
                    originalQtys[element.id] = element.quantity;
                });
                setOriginalQuantities(originalQtys);
                setTotal(t);
            }
        } catch (error) { }
    };

    const redirectToDetail = (slug: any) => {
        window.location.href = "/product/" + slug;
    };

    const handleQuantityChange = (itemId: number, newQuantity: number) => {
        const currentQuantity = pendingUpdates[itemId] ?? cart.find((item: any) => item.id === itemId)?.quantity ?? 0;

        const updatedQuantity = newQuantity === currentQuantity + 1 ? currentQuantity + 1 :
            newQuantity === currentQuantity - 1 ? currentQuantity - 1 :
                newQuantity;

        setPendingUpdates(prev => ({
            ...prev,
            [itemId]: updatedQuantity
        }));
        setIsUpdating(true);

        const updatedCart = cart.map((item: any) => {
            if (item.id === itemId) {
                return {
                    ...item,
                    quantity: updatedQuantity
                };
            }
            return item;
        });

        let newTotal = 0;
        updatedCart.forEach((element: any) => {
            newTotal += Number(element.variant.product.price_sale) *
                (element.id === itemId ? updatedQuantity : element.quantity);
        });
        setTotal(newTotal);
    };

    useEffect(() => {
        const processUpdates = async () => {
            const updates = Object.entries(debouncedUpdates);
            if (updates.length > 0) {
                try {
                    for (const [itemId, quantity] of updates) {
                        await updateCart(quantity, Number(itemId));
                    }
                    await getCartDetail();
                    toast.success("Cập nhật giỏ hàng thành công!");
                    setPendingUpdates({});
                } catch (error) {
                    setPendingUpdates({});
                    setCart(prev => prev.map(item => ({
                        ...item,
                        quantity: originalQuantities[item.id]
                    })));
                    console.log(error);
                    const errorMessage = error.response?.data?.message || 'Cập nhật giỏ hàng thất bại. Vui lòng thử lại.';
                    toast.error(errorMessage);
                }
            }
            setIsUpdating(false);
        };

        processUpdates();
    }, [debouncedUpdates]);

    useEffect(() => {
        if (cart !== null) {
            getCartDetail();
        }
    }, []);

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
                        {cart && cart.length === 0 ? (
                            <tr>
                                <td colSpan="4" className="text-center">
                                    <span className="fw-bold">
                                        Không có mặt hàng nào!
                                    </span>
                                </td>
                            </tr>
                        ) : (
                            cart.map((p, index) => {
                                return (
                                    <tr key={index}>
                                        <td className="w-40">
                                            <div className="d-flex gap-3">
                                                <div
                                                    className="img-product"
                                                    onClick={() =>
                                                        redirectToDetail(p.slug)
                                                    }
                                                    style={{
                                                        backgroundImage: `url(${"https://bizweb.dktcdn.net/thumb/compact/100/456/060/products/888bf7e8-1bc4-4fba-90fa-4afa82b6d6dc-1741974435553.jpg?v=1741974439350"})`,
                                                    }}
                                                ></div>

                                                <div className="info d-flex flex-column justify-content-center align-items-start">
                                                    <span className="product-name mb-1">
                                                        {p?.variant?.product?.name}
                                                    </span>
                                                    <div className="product-variants d-flex gap-2 mb-2">
                                                        <span className="variant-info">
                                                            <small className="text-muted">Kích thước: </small>
                                                            <small className="fw-bold">{p.variant.size.name}</small>
                                                        </span>
                                                        <span className="variant-separator text-muted">|</span>
                                                        <span className="variant-info">
                                                            <small className="text-muted">Màu sắc: </small>
                                                            <span
                                                                className="color-preview d-inline-block rounded-circle me-1"
                                                                style={{
                                                                    width: '12px',
                                                                    height: '12px',
                                                                    backgroundColor: p.variant.color.code,
                                                                    border: '1px solid #dee2e6',
                                                                    verticalAlign: 'middle'
                                                                }}
                                                            ></span>
                                                            <small className="fw-bold">{p.variant.color.name}</small>
                                                        </span>
                                                    </div>
                                                    <a
                                                        onClick={() =>
                                                            handleQuantityChange(
                                                                p.id,
                                                                0
                                                            )
                                                        }
                                                        className="delete-btn d-flex align-items-center text-decoration-none"
                                                    >
                                                        <i className="fas fa-trash-alt me-1"></i>
                                                        <span>Xóa</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td className="align-middle w-20">
                                            <strong className="price">
                                                {FormatCurrency(
                                                    p.variant.product.price_sale
                                                ) || "200,000"}
                                                đ
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
                                                        handleQuantityChange(
                                                            p.id,
                                                            (pendingUpdates[p.id] ?? p.quantity) - 1
                                                        )
                                                    }
                                                >
                                                    <i className="bi bi-dash"></i>
                                                </button>

                                                <input
                                                    type="text"
                                                    className="fw-bold form-control text-center"
                                                    value={pendingUpdates[p.id] ?? p.quantity}
                                                    aria-label="Quantity"
                                                    readOnly
                                                />

                                                <button
                                                    className="btn btn-outline-dark"
                                                    type="button"
                                                    id="button-plus"
                                                    onClick={() =>
                                                        handleQuantityChange(
                                                            p.id,
                                                            (pendingUpdates[p.id] ?? p.quantity) + 1
                                                        )
                                                    }
                                                >
                                                    <i className="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td className="align-middle w-20">
                                            <strong className="price">
                                                {FormatCurrency(
                                                    p.variant.product
                                                        .price_sale * p.quantity
                                                ) || "400,000"}
                                                đ
                                            </strong>{" "}
                                        </td>
                                    </tr>
                                );
                            })
                        )}
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
                                <strong className="price">
                                    {FormatCurrency(total) || "200,000"}đ
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td colSpan={2} className="">
                                <a
                                    href="/checkout"
                                    className="btn btn-dark col-12"
                                    disabled={isUpdating}
                                    style={{
                                        opacity: isUpdating ? 0.65 : 1,
                                        pointerEvents: isUpdating ? 'none' : 'auto'
                                    }}
                                >
                                    {isUpdating ? 'Đang cập nhật...' : 'Thanh toán'}
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                {isUpdating && (
                    <div className="text-center mt-2">
                        <small className="text-muted">Đang cập nhật giỏ hàng...</small>
                    </div>
                )}
            </div>
        </div>
    );
};

export default Cart;
