import './bootstrap';
import { BrowserRouter, Navigate, Route, Routes } from "react-router-dom";
import Layout from "./components/Layout";
import Home from "./pages/Home";
import ProductDetail from "./pages/ProductDetail";
import BlogDetail from "./pages/BlogDetail";
import Cart from "./pages/Cart";
import Login from './pages/Login';
import Signup from './pages/Signup';
import Checkout from './pages/Checkout';
import BlogList from './pages/BlogList';
import Search from './pages/Search';
import Profile from './pages/Profile';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { AuthProvider } from './context/AuthContext';
import { ProtectedRoute, AuthRoute } from './components/ProtectedRoute';

function App() {
    return (
        <div>
            <ToastContainer />
            <BrowserRouter>
                <AuthProvider>
                    <Routes>
                        {/* Public routes */}
                        <Route path="/" element={<Layout />}>
                            <Route index element={<Home />} />
                            <Route path="/home" element={<Navigate to="/" replace />} />
                            <Route path="/product/:slug" element={<ProductDetail />} />
                            <Route path="/blog-detail" element={<BlogDetail />} />
                            <Route path="/cart" element={<Cart />} />
                            <Route path='/login' element={
                                <AuthRoute>
                                    <Login />
                                </AuthRoute>
                            } />
                            <Route path='/signup' element={
                                <AuthRoute>
                                    <Signup />
                                </AuthRoute>
                            } />
                            <Route path='/checkout' element={<Checkout />} />
                            <Route path='/blog-list' element={<BlogList />} />
                            <Route path='/search' element={<Search />} />
                            <Route path='/profile' element={
                                <ProtectedRoute>
                                    <Profile />
                                </ProtectedRoute>
                            } />
                        </Route>
                    </Routes>
                </AuthProvider>
            </BrowserRouter >
        </div>
    );
}

export default App
