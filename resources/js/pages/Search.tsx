import { useEffect, useState } from "react";
import Product from "../components/Product";
import "./Search.scss";
import { useNavigate, useLocation } from "react-router-dom";
import { getAll, getAllByCategory } from "../services/ProductService";
import { fetchAll } from "../services/CategoryService";
import { FormatCurrency } from "../utils/FormatCurrency";

function Search() {
    const [priceRange, setPriceRange] = useState({ min: "", max: "" });
    const [selectedType, setSelectedType] = useState("");
    const [searchType, setSearchType] = useState("name");
    const [products, setProducts] = useState([]);
    const [categories, setCategories] = useState([]);
    const [keyword, setKeyword] = useState("");
    const [filterTitle, setFilterTitle] = useState("");
    const navigate = useNavigate();
    const location = useLocation();

    const getAllProducts = async () => {
        try {
            const res = await getAll();
            if (res.data && res.data.data) {
                console.log(res.data.data);
                setProducts(res.data.data);
            }
        } catch (error) {
            console.log("Detected error:", error);
        }
    };

    const getAllCategories = async () => {
        try {
            const res = await fetchAll();
            if (res.data && res.data.data) {
                console.log(res.data.data);
                setCategories(res.data.data);
            }
        } catch (error) {
            console.log("Detected error:", error);
        }
    };

    const redirectToDetail = (slug: any) => {
        navigate("/product/" + slug);
    };

    const handlePriceChange = (e: any) => {
        const { name, value } = e.target;
        setPriceRange({ ...priceRange, [name]: value });
    };

    const handleTypeChange = async (type: any) => {
        const res = await getAllByCategory(type);
        setProducts(res.data.data);
        setFilterTitle("Lọc theo loại: " + type);
        setSelectedType(type);
    };

    const search = async () => {
        try {
            const params = {
                [searchType]: keyword,
            };
            const res = await getAll(params);
            if (res.data && res.data.data) {
                console.log(res.data.data);
                setProducts(res.data.data);
                setFilterTitle("Lọc theo " + searchType + ": " + keyword);
            }
        } catch (error) {
            console.log("Detected error:", error);
        }
    };

    const applyFilters = async () => {
        console.log("Min Price:", priceRange.min);
        console.log("Max Price:", priceRange.max);
        console.log("Selected Types:", selectedType);
        setSelectedType("");
        try {
            const params = {
                min_price: priceRange.min,
                max_price: priceRange.max,
            };
            const res = await getAll(params);
            if (res.data && res.data.data) {
                console.log(res.data.data);
                setProducts(res.data.data);
                setFilterTitle(
                    "Lọc theo giá: " +
                    FormatCurrency(priceRange.min) +
                    "đ - " +
                    FormatCurrency(priceRange.max) +
                    "đ"
                );
            }
        } catch (error) {
            console.log("Detected error:", error);
        }
    };

    useEffect(() => {
        const performInitialSearch = async () => {
            const state = location.state as { initialSearchType?: string; initialKeyword?: string };
            if (state?.initialKeyword) {
                setSearchType('name');
                setKeyword(state.initialKeyword);
                try {
                    const params = {
                        name: state.initialKeyword
                    };
                    const res = await getAll(params);
                    if (res.data && res.data.data) {
                        setProducts(res.data.data);
                        setFilterTitle("Lọc theo tên sản phẩm: " + state.initialKeyword);
                    }
                } catch (error) {
                    console.log("Detected error:", error);
                }
            } else {
                getAllProducts();
            }
            getAllCategories();
        };

        performInitialSearch();
    }, [location.state]);

    return (
        <div className="container mt-5">
            <div className="nav d-flex align-items-center mb-2">
                <a href="" className="text-decoration-none text-dark">
                    Trang chủ
                </a>
                <span className="mx-2">/</span>
                <span className="text-muted">Danh sách sản phẩm</span>
            </div>
            <h2 className="my-4">DANH SÁCH SẢN PHẨM</h2>

            <div className="row">
                <div className="col-lg-3 mt-3">
                    <div className="search-bar mb-5">
                        <p className="text-dark text-bold fw-bold">Tìm Kiếm:</p>
                        <select
                            className="form-select me-2 mb-2 col-12"
                            value={searchType}
                            onChange={(e) => setSearchType(e.target.value)}
                        >
                            <option disabled value="">Tìm kiếm theo</option>
                            <option value="name">Tên sản phẩm</option>
                            <option value="sku">SKU</option>
                        </select>

                        <input
                            type="text"
                            className="form-control col-12 mb-2"
                            value={keyword}
                            onChange={(e) => setKeyword(e.target.value)}
                            placeholder="Nhập từ khóa..."
                            style={{ borderRadius: "0.25rem" }}
                        />

                        <button
                            className="btn btn-primary col-12"
                            onClick={search}
                            type="button"
                            style={{ borderRadius: "0" }}
                        >
                            Tìm kiếm
                        </button>

                    </div>
                    <div className="card p-3 shadow-sm">
                        <span className="fw-bold mb-3">Mức Giá:</span>
                        <div className="d-flex mb-3">
                            <input
                                type="number"
                                name="min"
                                className="form-control me-2"
                                placeholder="Từ"
                                value={priceRange.min}
                                onChange={handlePriceChange}
                            />
                            <input
                                type="number"
                                name="max"
                                className="form-control"
                                placeholder="Đến"
                                value={priceRange.max}
                                onChange={handlePriceChange}
                            />
                        </div>
                        <button
                            className="btn btn-dark mb-4 w-100"
                            onClick={applyFilters}
                        >
                            Áp Dụng Giá
                        </button>

                        <span className="fw-bold mb-3">Loại:</span>
                        {categories.map((c, index) => (
                            <div className="form-check mb-2" key={index}>
                                <input
                                    className="form-check-input"
                                    type="checkbox"
                                    checked={selectedType === c.slug}
                                    onChange={() => handleTypeChange(c.slug)}
                                />
                                <label className="form-check-label">
                                    {c.name}
                                </label>
                            </div>
                        ))}
                    </div>
                </div>

                <div className="col-lg-8 offset-lg-1 mt-3">
                    <span className="font-italic">{filterTitle}</span>
                    {products.length === 0 ? (
                        <div className="mt-3 fw-semibold fst-italic">
                            <span>Không tìm thấy Sản phẩm thỏa mãn!</span>
                        </div>
                    ) : ''}
                    <ul className="list-group mt-3">
                        {products &&
                            products.map((p, index) => (
                                <li
                                    key={index}
                                    className="list-group-item list-group-item-action"
                                    onClick={() => redirectToDetail(p.slug)}
                                >
                                    <Product p={p} />
                                </li>
                            ))}
                    </ul>
                </div>
            </div>
        </div>
    );
}
export default Search;
