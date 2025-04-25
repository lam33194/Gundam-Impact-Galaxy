import { useState } from "react";
import { toast } from "react-toastify";
import { useNavigate } from "react-router-dom";
import "./ForgetPassword.scss";

function ForgetPassword() {
    const navigate = useNavigate();
    const [email, setEmail] = useState("");
    const [error, setError] = useState("");
    const [isSubmitting, setIsSubmitting] = useState(false);

    const validateEmail = (email: string) => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setError("");

        // Validate empty
        if (!email.trim()) {
            setError("Vui lòng nhập email");
            return;
        }

        // Validate email format
        if (!validateEmail(email)) {
            setError("Vui lòng nhập đúng định dạng email");
            return;
        }

        setIsSubmitting(true);
        try {
            const formData = new FormData();
            formData.append('email', email.trim());

            const response = await fetch('/api/v1/auth/forgot-password', {
                method: 'POST',
                body: formData,
            });

            const data = await response.json();

            if (response.ok) {
                toast.success("Gửi yêu cầu thành công. Vui lòng kiểm tra email của bạn.", {
                    onClose: () => navigate('/login'),
                    autoClose: 1000
                });
                setEmail("");
            } else {
                toast.error(data.message || "Có lỗi xảy ra. Vui lòng thử lại.", {
                    autoClose: 1000
                });
            }
        } catch (error: any) {
            console.error('Forgot password error:', error);
            toast.error("Có lỗi xảy ra. Vui lòng thử lại sau.");
        } finally {
            setIsSubmitting(false);
        }
    };

    return (
        <div className="forget-password-container d-flex m-auto row col-lg-8">
            <div className="left d-flex flex-column col-lg-8 p-5">
                <form onSubmit={handleSubmit}>
                    <div className="form-group">
                        <h4 className="fw-bold mb-4">Quên mật khẩu</h4>
                        <p className="text-muted mb-4">
                            Vui lòng nhập địa chỉ email bạn đã đăng ký. Chúng tôi sẽ gửi hướng dẫn đặt lại mật khẩu vào email của bạn.
                        </p>
                        <div className="mb-4">
                            <label htmlFor="email" className="form-label fw-bold">
                                Email
                            </label>
                            <input
                                type="text"
                                id="email"
                                className={`form-control form-control-lg ${error ? 'is-invalid' : ''}`}
                                placeholder="Nhập email của bạn"
                                value={email}
                                onChange={(e) => {
                                    setEmail(e.target.value);
                                    setError("");
                                }}
                                disabled={isSubmitting}
                            />
                            {error && <div className="invalid-feedback">{error}</div>}
                        </div>
                        <button
                            type="submit"
                            className="btn btn-lg btn-dark col-12"
                            disabled={isSubmitting}
                        >
                            {isSubmitting ? 'Đang gửi...' : 'Gửi yêu cầu'}
                        </button>

                        <div className="text-center mt-4">
                            <p className="mb-0">
                                Quay lại trang <a href="/login">đăng nhập</a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>

            <div className="right d-flex flex-column col-lg-4 text-light p-5">
                <h4 className="mb-4 fw-bold">Quyền lợi với thành viên</h4>
                <ul className="mb-4">
                    <li>
                        <i className="fa-solid fa-fire me-2"></i>Dịch vụ đóng gói riêng
                    </li>
                    <li>
                        <i className="fa-solid fa-fire me-2"></i>Tích điểm đặc quyền
                    </li>
                    <li>
                        <i className="fa-solid fa-fire me-2"></i>Quà tặng bí mật
                    </li>
                    <li>
                        <i className="fa-solid fa-fire me-2"></i>Chăm sóc khách hàng 1-1
                    </li>
                    <li>
                        <i className="fa-solid fa-fire me-2"></i>Chi tiết hơn về chương
                        trình hội viên, bạn có thể <a href="">xem tại đây</a>
                    </li>
                </ul>
                <button className="btn btn-lg col-7">
                    <a href="/signup" style={{ color: '#fff' }}>Đăng ký</a>
                </button>
            </div>
        </div>
    );
}

export default ForgetPassword;