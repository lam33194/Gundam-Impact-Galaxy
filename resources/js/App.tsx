import './bootstrap';
import { BrowserRouter, Navigate, Route, Routes } from "react-router-dom";
import Layout from "./components/Layout";
import Home from "./pages/Home";
import ProductDetail from "./pages/ProductDetail";
import AdminDashBoard from "./pages/admin/AdminDashboard";
import AdminLayout from "./components/AdminLayout";
import BlogDetail from "./pages/BlogDetail";
import Cart from "./pages/Cart";
import ProductList from "./pages/admin/product_management/ProductList";
import Login from './pages/Login';
import Signup from './pages/Signup';
import Checkout from './pages/Checkout';
import BlogList from './pages/BlogList';


function App() {
    return (
        <BrowserRouter>
            <Routes>
                {/* Public routes */}
                <Route path="/" element={<Layout />}>
                    <Route index element={<Home />} />
                    <Route path="/home" element={<Navigate to="/" replace />} />
                    <Route path="/product-detail" element={<ProductDetail />} />
                    <Route path="/blog-detail" element={<BlogDetail />} />
                    <Route path="/cart" element={<Cart />} />
                    <Route path='/login' element={<Login/>} />
                    <Route path='/signup' element={<Signup/>} />
                    <Route path='/checkout' element={<Checkout/>} />
                    <Route path='/blog-list' element={<BlogList/>} />
                </Route>
                {/* Admin routes */}
                <Route path="/admin" element={<AdminLayout />}>
                    <Route path="/admin" element={<AdminDashBoard />} />
                    <Route path="/admin/products" element={< ProductList />} />
                </Route>
            </Routes>
        </BrowserRouter >
    );
}

export default App
