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
import { District, Province, Ward } from "../interfaces/Location";

const Checkout = () => {
    const { user: authUser } = useAuth();
    const [isLoading, setIsLoading] = useState(true);
    const [cart, setCart] = useState([]);
    const [total, setTotal] = useState(0);

    // Add location states
    const [provinces, setProvinces] = useState<Province[]>([]);
    const [districts, setDistricts] = useState<District[]>([]);
    const [wards, setWards] = useState<Ward[]>([]);

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

    const [voucherCode, setVoucherCode] = useState("");

    const handleChange = (e: any) => {
        const { name, value } = e.target;
        setFormData({
            ...formData,
            [name]: value,
        });
    };

    const handleVoucherChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setVoucherCode(e.target.value);
    };

    const createOrder = async () => {
        const { user_address, province, district, ward } = formData;
        const fullAddress = `${user_address}, ${ward}, ${district}, ${province}`;

        const data = {
            user_name: formData.user_name,
            user_email: formData.user_email,
            user_phone: formData.user_phone,
            user_address: fullAddress,
            user_note: formData.user_note || "",
            type_payment: formData.type_payment,
            voucher_code: voucherCode || null
        };
        try {
            const res = await addOrder(data);
            if (res && res.data) {
                if (formData.type_payment === 'vnpay' && res.data.data.payment_url) {
                    // Redirect directly to VNPAY URL
                    window.location.href = res.data.data.payment_url;
                } else {
                    toast.success("Đặt hàng thành công!");
                    setTimeout(() => {
                        window.location.href = "/order-history";
                    }, 1000);
                }
            }
        } catch (error: any) {
            console.error('Lỗi xảy ra:', error);
            if (error.response?.data?.message) {
                toast.error(error.response.data.message);
            } else {
                toast.error("Có lỗi xảy ra, vui lòng thử lại!");
            }
        }
    };

    const getCartDetail = async () => {
        try {
            const res = await getCart({ include: 'variant' });
            if (res && res.data) {
                setCart(res.data.data);
                let t = 0;
                res.data.data.forEach((element: any) => {
                    let tempPrice = element.variant.product.price_sale != 0
                        ? element.variant.product.price_sale
                        : element.variant.product.price_regular;

                    t += Number(tempPrice) * Number(element.quantity);
                    // t +=
                    // Number(element.variant.product.price_sale) *
                    // Number(element.quantity);
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
<<<<<<< Updated upstream
=======
        } catch (error) {
            console.error('Failed to load location data:', error);
        }
    };

    const fetchUserData = async () => {
        try {
            if (!authUser?.id) return;

            setIsLoading(true);
            const response = await getUserById(authUser.id, {
                include: 'addresses'
            });
            const userData = response.data.data;
            console.log(userData)

            // Store all addresses
            setAddresses(userData.addresses || []);

            // Find primary address or first address
            const primaryAddress = userData.addresses?.find((addr: any) => addr.is_primary === 1) ||
                userData.addresses?.[0];
                console.log('de' + primaryAddress)

            if (primaryAddress) {
                setSelectedAddressId(primaryAddress.id.toString()); // Convert to string
                setFormData({
                    ...formData,
                    user_name: userData.name || '',
                    user_email: userData.email || '',
                    user_phone: userData.phone || '',
                    user_address: primaryAddress.address || '',
                    province: primaryAddress.city || '',
                    district: primaryAddress.district || '',
                    ward: primaryAddress.ward || '',
                    ship_user_name: isOrderForOther ? '' : userData.name || '',
                    ship_user_email: isOrderForOther ? '' : userData.email || '',
                    ship_user_phone: isOrderForOther ? '' : userData.phone || '',
                });

                await loadLocationData(
                    primaryAddress.city || '',
                    primaryAddress.district || '',
                    primaryAddress.ward || ''
                );
            }
        } catch (error) {
            console.error('Failed to fetch user data:', error);
        } finally {
            setIsLoading(false);
        }
    };

    const handleAddressChange = async (addressId: string) => {
        setSelectedAddressId(addressId);
        const selectedAddress = addresses.find(addr => addr.id.toString() === addressId);

        if (selectedAddress) {
            // Reset form data
            setFormData(prev => ({
                ...prev,
                user_name: selectedAddress.name || prev.user_name,
                user_phone: selectedAddress.phone || prev.user_phone,
                user_address: selectedAddress.address || '',
                province: selectedAddress.city || '',
                district: selectedAddress.district || '',
                ward: selectedAddress.ward || '',
            }));

            // Reset selection states
            setSelectedProvince('');
            setSelectedDistrict('');
            setSelectedWard('');
            setDistricts([]);
            setWards([]);

            // Load location data
            await loadLocationData(
                selectedAddress.city || '',
                selectedAddress.district || '',
                selectedAddress.ward || ''
            );
        } else {
            // Reset everything if no address is selected
            setFormData(prev => ({
                ...prev,
                user_address: '',
                province: '',
                district: '',
                ward: '',
            }));
            setSelectedProvince('');
            setSelectedDistrict('');
            setSelectedWard('');
            setDistricts([]);
            setWards([]);
>>>>>>> Stashed changes
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
                const response = await getUserById(authUser.id, {
                    include: 'addresses'
                });
                const userData = response.data.data;
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
                            {/* Personal info fields */}
                            <div className="form-floating mb-3">
                                <input
                                    type="text"
                                    className="form-control"
                                    name="user_name"
                                    placeholder="Họ và tên"
                                    value={formData.user_name}
                                    onChange={handleChange}
                                />
                                <label htmlFor="floatingFullName">Họ và tên</label>
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
                                <label htmlFor="floatingPhone">Số điện thoại</label>
                            </div>

                            <h6 className="mt-4 mb-3">Địa chỉ nhận hàng</h6>

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

                            {/* Location selects */}
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
                                checked={formData.type_payment === "momo"}
                                value="momo"
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
                                checked={formData.type_payment === "vnpay"}
                                value="vnpay"
                                onChange={handleChange}
                            />
                            <label
                                htmlFor="vnpayPaymentMethod"
                                className="form-check-label d-flex align-items-center ms-2"
                            >
                                Vnpay
                            </label>
                        </div>

                        <h5 className="mt-4">Ghi chú</h5>
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
            </div>

            <div className="cart-info col-4">
                <h4 className="title">Đơn hàng ({cart.length} sản phẩm)</h4>
                <div className="line"></div>
                <div className="cart-product d-flex flex-column gap-4 my-3">
                    {cart.map((item: any, index) => {
                        const product_price = item.variant.product.price_sale != 0
                            ? item.variant.product.price_sale
                            : item.variant.product.price_regular;
                        return (
                            <div
                                key={index}
                                className="cart-product-item d-flex align-items-start justify-content-between"
                            >
                                <div className="d-flex gap-2">
                                    <div
                                        className="cart-product-item-image"
                                        style={{
                                            // backgroundImage: `url(${STORAGE_URL + item.variant.image})`,
                                            backgroundImage: item.variant.image == null
                                                ? `url(${STORAGE_URL + item.variant.product?.thumb_image})`
                                                : `url(${STORAGE_URL + item.variant?.image})`,
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
                                    {/* {FormatCurrency(product_price * item.quantity)}đ */}
                                    {FormatCurrency(product_price)}đ
                                </span>
                            </div>
                        )
                    })}
                </div>

                <div className="enter-coupon d-flex gap-1">
                    <div className="form-floating col-8">
                        <input
                            className="form-control"
                            id="floatingNote"
                            placeholder="Nhập mã giảm giá"
                            value={voucherCode}
                            onChange={handleVoucherChange}
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
