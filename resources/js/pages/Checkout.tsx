import { useEffect, useState } from "react";
import "./Checkout.scss";
import "bootstrap/dist/css/bootstrap.min.css";
import { getCart } from "../services/CartService";
import { FormatCurrency } from "../utils/FormatCurrency";
import { addOrder } from "../services/OrderService";
import { toast } from "react-toastify";
import { useAuth } from "../context/AuthContext";
import { getUserById } from "../services/UserService";
import { getProvinces, getDistricts, getWards } from "../services/LocationService";
import { STORAGE_URL } from "../utils/constants";

const Checkout = () => {
    const { user: authUser } = useAuth();
    const [isLoading, setIsLoading] = useState(true);
    const [cart, setCart] = useState([]);
    const [total, setTotal] = useState(0);

    // Add location states
    const [provinces, setProvinces] = useState([]);
    const [districts, setDistricts] = useState([]);
    const [wards, setWards] = useState([]);

    const [selectedProvince, setSelectedProvince] = useState("");
    const [selectedDistrict, setSelectedDistrict] = useState("");
    const [selectedWard, setSelectedWard] = useState("");

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
        } catch (error) { }
    };

    const loadLocationData = async (city: string, district: string, ward: string) => {
        const provincesResponse = await getProvinces();
        setProvinces(provincesResponse.data);

        if (city) {
            const matchingProvince = provincesResponse.data.find(p => p.name === city);
            if (matchingProvince) {
                setSelectedProvince(matchingProvince.id);

                const districtsResponse = await getDistricts(matchingProvince.id);
                setDistricts(districtsResponse.data);

                if (district) {
                    const matchingDistrict = districtsResponse.data.find(d => d.name === district);
                    if (matchingDistrict) {
                        setSelectedDistrict(matchingDistrict.id);

                        const wardsResponse = await getWards(matchingDistrict.id);
                        setWards(wardsResponse.data);

                        if (ward) {
                            const matchingWard = wardsResponse.data.find(w => w.name === ward);
                            if (matchingWard) {
                                setSelectedWard(matchingWard.id);
                            }
                        }
                    }
                }
            }
        }
    };

    useEffect(() => {
        getCartDetail();
    }, []);

    useEffect(() => {
        const fetchUserData = async () => {
            try {
                if (!authUser?.id) return;

                setIsLoading(true);
                const response = await getUserById(authUser.id, { include: 'addresses' });
                const userData = response.data.data[0];
                const userAddress = userData.addresses?.[0];

                setFormData({
                    ...formData,
                    user_name: userData.name || '',
                    user_email: userData.email || '',
                    user_phone: userData.phone || '',
                    user_address: userAddress?.address || '',
                    province: userAddress?.city || '',
                    district: userAddress?.district || '',
                    ward: userAddress?.ward || '',
                });

                await loadLocationData(
                    userAddress?.city || '',
                    userAddress?.district || '',
                    userAddress?.ward || ''
                );
            } catch (error) {
                console.error('Failed to fetch user data:', error);
            } finally {
                setIsLoading(false);
            }
        };

        fetchUserData();
    }, [authUser?.id]);

    useEffect(() => {
        if (selectedProvince) {
            const fetchDistricts = async () => {
                try {
                    setSelectedWard('');
                    setWards([]);
                    setSelectedDistrict('');
                    setDistricts([]);

                    const response = await getDistricts(selectedProvince);
                    setDistricts(response.data);

                    setFormData(prev => ({
                        ...prev,
                        province: provinces.find(p => p.id === selectedProvince)?.name || '',
                        district: '',
                        ward: ''
                    }));
                } catch (error) {
                    console.error('Failed to fetch districts:', error);
                }
            };
            fetchDistricts();
        }
    }, [selectedProvince]);

    useEffect(() => {
        if (selectedDistrict) {
            const fetchWards = async () => {
                try {
                    const response = await getWards(selectedDistrict);
                    setWards(response.data);

                    setFormData(prev => ({
                        ...prev,
                        district: districts.find(d => d.id === selectedDistrict)?.name || '',
                        ward: ''
                    }));
                } catch (error) {
                    console.error('Failed to fetch wards:', error);
                }
            };
            fetchWards();
        }
    }, [selectedDistrict]);

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
                                <select
                                    className="form-select"
                                    value={selectedProvince}
                                    onChange={(e) => setSelectedProvince(e.target.value)}
                                >
                                    <option value="">Chọn Tỉnh/Thành phố</option>
                                    {provinces.map(province => (
                                        <option key={province.id} value={province.id}>
                                            {province.name}
                                        </option>
                                    ))}
                                </select>
                                <label>Tỉnh/Thành phố</label>
                            </div>

                            <div className="form-floating mb-3">
                                <select
                                    className="form-select"
                                    value={selectedDistrict}
                                    onChange={(e) => setSelectedDistrict(e.target.value)}
                                    disabled={!selectedProvince}
                                >
                                    <option value="">Chọn Quận/Huyện</option>
                                    {districts.map(district => (
                                        <option key={district.id} value={district.id}>
                                            {district.name}
                                        </option>
                                    ))}
                                </select>
                                <label>Quận/Huyện</label>
                            </div>

                            <div className="form-floating mb-3">
                                <select
                                    className="form-select"
                                    value={selectedWard}
                                    onChange={(e) => {
                                        setSelectedWard(e.target.value);
                                        setFormData(prev => ({
                                            ...prev,
                                            ward: wards.find(w => w.id === e.target.value)?.name || ''
                                        }));
                                    }}
                                    disabled={!selectedDistrict}
                                >
                                    <option value="">Chọn Xã/Phường</option>
                                    {wards.map(ward => (
                                        <option key={ward.id} value={ward.id}>
                                            {ward.name}
                                        </option>
                                    ))}
                                </select>
                                <label>Xã/Phường</label>
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
                    {cart.map((item: any, index) => (
                        <div
                            key={index}
                            className="cart-product-item d-flex align-items-start justify-content-between"
                        >
                            <div className="d-flex gap-2">
                                <div
                                    className="cart-product-item-image"
                                    style={{
                                        backgroundImage: `url(${STORAGE_URL + item.variant.image || "https://bizweb.dktcdn.net/thumb/medium/100/456/060/products/f84eb124-0644-448c-8e8c-30776876301d-1735131922675.jpg?v=1735134125243"})`,
                                        height: "60px",
                                        width: "60px",
                                        backgroundSize: "cover",
                                        backgroundPosition: "center",
                                        flexShrink: 0
                                    }}
                                ></div>
                                <div className="product-info d-flex flex-column">
                                    <span className="item-name fw-bold">
                                        {item.variant.product.name}
                                    </span>
                                    <div className="variant-info">
                                        <small className="text-muted">
                                            {item.variant.size.name} |
                                            <span
                                                className="color-dot"
                                                style={{
                                                    display: 'inline-block',
                                                    width: '10px',
                                                    height: '10px',
                                                    borderRadius: '50%',
                                                    backgroundColor: item.variant.color.code,
                                                    border: '1px solid #dee2e6',
                                                    marginLeft: '4px',
                                                    marginRight: '4px',
                                                    verticalAlign: 'middle'
                                                }}
                                            ></span>
                                            {item.variant.color.name}
                                        </small>
                                    </div>
                                    <div className="quantity-info">
                                        <small className="text-muted">
                                            Số lượng: {item.quantity}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <span className="item-price text-danger fw-bold">
                                {FormatCurrency(item.variant.product.price_sale * item.quantity)}đ
                            </span>
                        </div>
                    ))}
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
