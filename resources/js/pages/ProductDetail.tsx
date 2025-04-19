import { useNavigate, useParams } from "react-router-dom";
import Blog from "../components/Blog";
import "./ProductDetail.scss";
import { useEffect, useState } from "react";
import { getDetail } from "../services/ProductService";
import { addToCart } from "../services/CartService";
import { toast } from "react-toastify";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";
import Slider from "react-slick";
import { FormatCurrency } from "../utils/FormatCurrency";

const ProductDetail = () => {
    const nav = useNavigate();

    const settings = {
        dots: true,
        infinite: true,
        speed: 500,
        slidesToShow: 2,
        slidesToScroll: 1,
    };

    const [content, setContent] = useState("");
    const [rating, setRating] = useState(0);
    const [hoverRating, setHoverRating] = useState(0);
    const [images, setImages] = useState<any>([]);
    const [imagePreviews, setImagePreviews] = useState<any>([]);

    const handleImageUpload = (e: any) => {
        const files = Array.from(e.target.files);
        if (files.length + images.length > 5) {
            alert("Tối đa 5 ảnh được phép tải lên");
            return;
        }

        const newImages = [...images, ...files];
        setImages(newImages.slice(0, 5));

        // Tạo preview
        const previews = files.map((file: any) => URL.createObjectURL(file));
        setImagePreviews([...imagePreviews, ...previews].slice(0, 5));
    };

    const removeImage = (index: any) => {
        const newImages = [...images];
        newImages.splice(index, 1);
        setImages(newImages);

        const newPreviews = [...imagePreviews];
        URL.revokeObjectURL(newPreviews[index]); // Giải phóng bộ nhớ
        newPreviews.splice(index, 1);
        setImagePreviews(newPreviews);
    };

    const handleSubmit = (e: any) => {
        e.preventDefault();
        // Xử lý submit ở đây
        console.log({ content, rating, images });
    };

    const { slug } = useParams();
    const [quantity, setQuantity] = useState(1);
    const [product, setProduct] = useState<any>(null);
    const [productVariant, setProductVariant] = useState({});
    const [selectedSize, setSelectedSize] = useState<number | null>(null);
    const [selectedColor, setSelectedColor] = useState<number | null>(null);

    const getUniqueSizes = () => {
        if (!product?.variants) return [];
        const sizes = product.variants.map((v: { size: any }) => v.size);
        return [...new Map(sizes.map((s: { id: any }) => [s.id, s])).values()];
    };

    const getUniqueColors = () => {
        if (!product?.variants) return [];
        const colors = product.variants.map((v: { color: any }) => v.color);
        return [...new Map(colors.map((c: { id: any }) => [c.id, c])).values()];
    };

    const getProductDetail = async () => {
        try {
            const res = await getDetail(slug);
            console.log(res);
            if (res && res.data) {
                setProduct(res.data);
                console.log(res.data.variants[0].id);
            }
        } catch (error) {
            console.log(error);
        }
    };

    const updateCart = async (index?: any) => {
        try {
            if (!selectedSize || !selectedColor) {
                toast.error("Vui lòng chọn kích thước và màu sắc!");
                return;
            }

            const selectedVariant = product.variants.find(
                (v: { size: { id: number }; color: { id: number } }) =>
                    v.size.id === selectedSize && v.color.id === selectedColor
            );

            if (!selectedVariant) {
                toast.error("Phiên bản sản phẩm không tồn tại!");
                return;
            }

            const res = await addToCart({
                product_variant_id: selectedVariant.id,
                quantity,
            });

            if (res?.data) {
                toast.success("Đã thêm vào giỏ hàng!");
            }
            if (index === -1) {
                nav("/cart");
            }
        } catch (error: any) {
            console.error("Lỗi xảy ra:", error);
            const errorMessage =
                error.response?.data?.message ||
                "Có lỗi xảy ra, vui lòng thử lại!";
            toast.error(errorMessage);
        }
    };

    useEffect(() => {
        if (slug) {
            getProductDetail();
        }
    }, [slug]);

    useEffect(() => {
        if (product?.variants && product.variants.length > 0) {
            const firstVariant = product.variants[0];
            setSelectedSize(firstVariant.size.id);
            setSelectedColor(firstVariant.color.id);
        }
    }, [product]);

    return (
        <div className="product-detail container d-flex">
            <div className="detail row col-9 gap-4">
                <div className="image col-lg-5">
                    <div
                        className="img"
                        style={{
                            backgroundImage: `url(${"https://bizweb.dktcdn.net/thumb/large/100/456/060/products/3fce7911-d633-4417-b263-a30ba273c623.jpg?v=1732162521390"})`,
                        }}
                    ></div>

                    <div className="col-lg-10">
                        <Slider {...settings}>
                            {product?.variants.map((variant: any) => (
                                <div
                                    key={variant.id}
                                    className="product-variant"
                                >
                                    <img
                                        className="product-variant"
                                        src={
                                            variant.imageu ||
                                            "https://bizweb.dktcdn.net/thumb/large/100/456/060/products/3fce7911-d633-4417-b263-a30ba273c623.jpg?v=1732162521390"
                                        }
                                        alt={`Slide ${variant.id}`}
                                        style={{
                                            width: "120px",
                                            height: "120px",
                                        }}
                                        onClick={() =>
                                            setProductVariant(variant)
                                        }
                                    />
                                </div>
                            ))}
                        </Slider>
                    </div>
                </div>

                <div className="info d-flex flex-column gap-1 col-lg-6 col-sm-12">
                    <h5 className="fw-bold mb-0">
                        {product !== null ? product.name : ""}
                    </h5>
                    <div className="d-flex gap-4">
                        <span>
                            Thương hiệu:&nbsp;
                            <strong>
                                {product !== null ? product.category.name : ""}
                            </strong>
                        </span>
                        <span>
                            Mã sản phẩm:&nbsp;
                            {product !== null ? product.sku : ""}
                        </span>
                    </div>
                    <span className="price">
                        {product !== null
                            ? FormatCurrency(product.price_sale)
                            : ""}
                        đ
                    </span>
                    <div className="line mb-2 mt-1"></div>
                    <span>5 Mã Giảm Giá</span>
                    <div className="coupon-lists d-flex gap-2">
                        <div className="coupon py-1 px-2 col-2">Giảm 5%</div>
                        <div className="coupon py-1 px-2 col-2">Giảm 5%</div>
                        <div className="coupon py-1 px-2 col-2">Giảm 5%</div>
                        <div className="coupon py-1 px-2 col-2">Giảm 5%</div>
                    </div>
                    <div className="line mb-2 mt-2"></div>

                    {/* Size Selection */}
                    <div className="variant-selection mb-3">
                        <span className="d-block mb-2">Kích thước:</span>
                        <div className="size-options d-flex gap-2 flex-wrap">
                            {getUniqueSizes().map((size) => (
                                <button
                                    key={size.id}
                                    className={`btn btn-outline-dark rounded-pill px-4 py-2 ${
                                        selectedSize === size.id ? "active" : ""
                                    }`}
                                    onClick={() => setSelectedSize(size.id)}
                                >
                                    {size.name}
                                </button>
                            ))}
                        </div>
                    </div>

                    {/* Color Selection */}
                    <div className="variant-selection mb-3">
                        <span className="d-block mb-2">Màu sắc:</span>
                        <div className="color-options d-flex gap-2 flex-wrap">
                            {getUniqueColors().map((color) => (
                                <button
                                    key={color.id}
                                    className={`btn btn-outline-dark rounded-pill px-4 py-2 d-flex align-items-center gap-2 ${
                                        selectedColor === color.id
                                            ? "active"
                                            : ""
                                    }`}
                                    onClick={() => setSelectedColor(color.id)}
                                >
                                    <span
                                        className="color-preview"
                                        style={{
                                            width: "20px",
                                            height: "20px",
                                            borderRadius: "50%",
                                            backgroundColor: color.code,
                                            border: "1px solid #dee2e6",
                                        }}
                                    ></span>
                                    {color.name}
                                </button>
                            ))}
                        </div>
                    </div>

                    <span>Số lượng:</span>
                    <div
                        className="input-group input-group-sm quantity-selector"
                        style={{ width: "100px" }}
                    >
                        <button
                            className="btn btn-outline-dark"
                            type="button"
                            id="button-minus"
                            onClick={() =>
                                quantity > 0
                                    ? setQuantity(quantity - 1)
                                    : setQuantity(0)
                            }
                        >
                            <i className="bi bi-dash"></i>
                        </button>

                        <input
                            type="text"
                            className="fw-bold form-control text-center"
                            value={quantity}
                            aria-label="Quantity"
                            readOnly
                        />

                        <button
                            className="btn btn-outline-dark"
                            type="button"
                            id="button-plus"
                            onClick={() => setQuantity(quantity + 1)}
                        >
                            <i className="bi bi-plus"></i>
                        </button>
                    </div>

                    <div className="d-flex gap-2 pay my-2">
                        <button
                            className="btn btn-dark col-10 d-flex flex-column"
                            onClick={() => updateCart(-1)}
                        >
                            <span>Thanh toán online hoặc ship COD</span>
                            <span className="fw-bold">Mua ngay</span>
                        </button>
                        <button
                            className="btn btn-dark col-2"
                            onClick={() => updateCart()}
                        >
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

                <div className="comment mt-5">
                    <h5>Đánh giá sản phẩm</h5>

                    <form onSubmit={handleSubmit} className="mt-3">
                        <div className="form-group mb-3">
                            <div className="star-rating">
                                {[1, 2, 3, 4, 5].map((star) => (
                                    <button
                                        key={star}
                                        type="button"
                                        className="btn p-1"
                                        onClick={() => setRating(star)}
                                        onMouseEnter={() =>
                                            setHoverRating(star)
                                        }
                                        onMouseLeave={() => setHoverRating(0)}
                                    >
                                        <i
                                            className="fa-solid fa-star"
                                            style={{
                                                color:
                                                    star <=
                                                    (hoverRating || rating)
                                                        ? "#ffc107"
                                                        : "#ddd",
                                            }}
                                        ></i>
                                    </button>
                                ))}
                            </div>
                        </div>
                        <div className="form-group mb-3">
                            <textarea
                                rows={4}
                                value={content}
                                onChange={(e) => setContent(e.target.value)}
                                placeholder="Nội dung bình luận"
                                className="form-control"
                            />
                        </div>

                        <div className="form-group mb-3">
                            <label>Tải lên ảnh (tối đa 5 ảnh)</label>
                            <div className="d-flex flex-wrap gap-2 mb-2">
                                {imagePreviews.map((preview: any, index: any) => (
                                    <div
                                        key={index}
                                        className="position-relative"
                                        style={{
                                            width: "120px",
                                            height: "120px",
                                        }}
                                    >
                                        <img
                                            src={preview}
                                            alt={`Preview ${index}`}
                                            className="img-thumbnail h-100 w-100 object-fit-cover"
                                        />
                                        <button
                                            type="button"
                                            className="btn btn-sm btn-secondary position-absolute top-0 end-0"
                                
                                            onClick={() => removeImage(index)}
                                        >
                                            <i className="fa-solid fa-xmark"  ></i>
                                        </button>
                                    </div>
                                ))}
                            </div>

                            <label className="btn btn-outline-secondary">
                                Chọn ảnh
                                <input
                                    type="file"
                                    multiple
                                    accept="image/*"
                                    onChange={handleImageUpload}
                                    className="d-none"
                                />
                            </label>
                            <small className="d-block text-muted">
                                Đã chọn: {images.length}/5 ảnh
                            </small>
                        </div>

                        <button
                            type="submit"
                            className="btn btn-primary"
                            disabled={
                                !content && !rating && images.length === 0
                            }
                        >
                            Gửi bình luận
                        </button>
                    </form>
                </div>
                <h3 className="text-center my-3">Danh sách Bình luận</h3>
                <div className="d-flex flex-column gap-3">
                    <div className="d-flex flex-column p-3 bg-light border-bottom pb-3">
                        <div className="d-flex justify-content-between align-items-start mb-2">
                            <h5 className="fw-bold mb-1">Nguyễn Văn A</h5>
                            <small className="text-muted">
                                2025-03-13 10:00
                            </small>
                        </div>
                        <div className="text-muted mb-2">
                            nguyen.a@example.com
                        </div>
                        <p className="mb-0">
                            Đây là một bình luận mẫu để hiển thị nội dung bình
                            luận của người dùng.
                        </p>
                    </div>

                    <div className="d-flex flex-column p-3 bg-light border-bottom pb-3">
                        <div className="d-flex justify-content-between align-items-start mb-2">
                            <h5 className="fw-bold mb-1">Trần Thị B</h5>
                            <small className="text-muted">
                                2025-03-13 11:30
                            </small>
                        </div>
                        <div className="text-muted mb-2">
                            tran.b@example.com
                        </div>
                        <p className="mb-0">
                            Đây là một bình luận khác để minh họa cách hiển thị.
                        </p>
                    </div>

                    <div className="d-flex flex-column p-3 bg-light">
                        <div className="d-flex justify-content-between align-items-start mb-2">
                            <h5 className="fw-bold mb-1">Lê Văn C</h5>
                            <small className="text-muted">
                                2025-03-13 12:45
                            </small>
                        </div>
                        <div className="text-muted mb-2">le.c@example.com</div>
                        <p className="mb-0">
                            Nội dung bình luận thứ ba của người dùng.
                        </p>
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
                        <img src="https://bizweb.dktcdn.net/100/456/060/themes/1004041/assets/ico_sv3.png?1743759267039" />
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
};

export default ProductDetail;
