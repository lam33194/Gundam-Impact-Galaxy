import { useEffect, useState } from "react";
import Product from "../components/Product";
import "./Search.scss";
import { useNavigate } from "react-router-dom";
import { getAll, getAllByCategory } from "../services/ProductService";
import { fetchAll } from "../services/CategoryService";
import { FormatCurrency } from "../utils/FormatCurrency";

function Search() {
    const [priceRange, setPriceRange] = useState({ min: "", max: "" });
    const [selectedType, setSelectedType] = useState('');
    const [products, setProducts] = useState([]);
    const [categories, setCategories] = useState([]);
    const [keyword, setKeyword] = useState("");
    const [filterTitle, setFilterTitle] = useState('');
    const navigate = useNavigate();

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
    

    const applyFilters = async() => {
        console.log("Min Price:", priceRange.min);
        console.log("Max Price:", priceRange.max);
        console.log("Selected Types:", selectedType);
        setSelectedType('')
        try {
            const params = {
                min_price: priceRange.min,
                max_price: priceRange.max,
            }
            const res = await getAll(params);
            if (res.data && res.data.data) {
                console.log(res.data.data);
                setProducts(res.data.data);
                setFilterTitle("Lọc theo giá: " + FormatCurrency(priceRange.min) + 'đ - ' + FormatCurrency(priceRange.max) + 'đ');
            }
        } catch (error) {
            console.log("Detected error:", error);
        }
    };

    useEffect(() => {
        getAllProducts();
        getAllCategories();
    }, []);
    return (
        <div className="container mt-5">
            <h1 className="text-center mb-4">Tìm kiếm sản phẩm</h1>
            <div className="input-group mb-4">
                <input
                    type="text"
                    className="form-control"
                    placeholder="Nhập tên sản phẩm..."
                    style={{ borderRadius: '0.25rem 0 0 0.25rem' }}
                />
                <button className="btn btn-primary" type="button" style={{ borderRadius: '0 0.25rem 0.25rem 0' }}>
                    Search
                </button>
            </div>

            <div className="row">
                <div className="col-lg-3 mt-5">
                    <div className="card p-3 shadow-sm">
                        <h4 className="mb-3">Mức Giá</h4>
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
                        <button className="btn btn-dark mb-4 w-100" onClick={applyFilters}>
                            Áp Dụng Giá
                        </button>

                        <h4 className="mb-3">Loại</h4>
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

                <div className="col-lg-8 offset-lg-1">
                    <h5 className="mt-3">DANH SÁCH SẢN PHẨM</h5>
                    <span className="mt-3 font-italic">{filterTitle}</span>
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
