import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { toast } from "react-toastify";
import { resetPassword } from "../services/AuthService";

function ResetPassword() {
    const navigate = useNavigate();
    const [formData, setFormData] = useState({
        email: '',
        token: '',
        password: '',
        password_confirmation: ''
    });
    const [error, setError] = useState("");
    const [isSubmitting, setIsSubmitting] = useState(false);

    useEffect(() => {
        const token = document.querySelector('meta[name="reset-token"]')?.getAttribute('content');
        const email = document.querySelector('meta[name="reset-email"]')?.getAttribute('content');

        if (!token || !email) {
            toast.error('Liên kết không hợp lệ hoặc đã hết hạn', {
                autoClose: 2000
            });
            navigate('/login');
            return;
        }

        setFormData(prev => ({
            ...prev,
            token,
            email
        }));
    }, []);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setError("");

        if (!formData.password) {
            setError("Vui lòng nhập mật khẩu mới");
            return;
        }

        if (formData.password.length < 6) {
            setError("Mật khẩu phải có ít nhất 6 ký tự");
            return;
        }

        if (formData.password !== formData.password_confirmation) {
            setError("Xác nhận mật khẩu không khớp");
            return;
        }

        setIsSubmitting(true);
        try {
            await resetPassword(formData);
            toast.success("Đặt lại mật khẩu thành công", {
                onClose: () => navigate('/login'),
                autoClose: 2000
            });
        } catch (error: any) {
            console.error('Reset password error:', error);
            const errorMessage = error.response?.data?.message || "Có lỗi xảy ra. Vui lòng thử lại.";
            toast.error(errorMessage, {
                autoClose: 2000
            });
        } finally {
            setIsSubmitting(false);
        }
    };

    return (
        <div className="reset-password-container d-flex m-auto row col-lg-8">
            <div className="left d-flex flex-column col-lg-8 p-5">
                <form onSubmit={handleSubmit}>
                    <div className="form-group">
                        <h4 className="fw-bold mb-4">Đặt lại mật khẩu</h4>
                        <p className="text-muted mb-4">
                            Vui lòng nhập mật khẩu mới cho tài khoản của bạn
                        </p>
                        <div className="mb-4">
                            <label className="form-label fw-bold">Email</label>
                            <input
                                type="email"
                                className="form-control form-control-lg"
                                value={formData.email}
                                disabled
                            />
                        </div>
                        <div className="mb-4">
                            <label htmlFor="password" className="form-label fw-bold">
                                Mật khẩu mới
                            </label>
                            <input
                                type="password"
                                id="password"
                                className={`form-control form-control-lg ${error ? 'is-invalid' : ''}`}
                                value={formData.password}
                                onChange={(e) => {
                                    setFormData(prev => ({
                                        ...prev,
                                        password: e.target.value
                                    }));
                                    setError("");
                                }}
                                disabled={isSubmitting}
                            />
                        </div>
                        <div className="mb-4">
                            <label htmlFor="password_confirmation" className="form-label fw-bold">
                                Xác nhận mật khẩu mới
                            </label>
                            <input
                                type="password"
                                id="password_confirmation"
                                className={`form-control form-control-lg ${error ? 'is-invalid' : ''}`}
                                value={formData.password_confirmation}
                                onChange={(e) => {
                                    setFormData(prev => ({
                                        ...prev,
                                        password_confirmation: e.target.value
                                    }));
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
                            {isSubmitting ? 'Đang xử lý...' : 'Đặt lại mật khẩu'}
                        </button>
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
                </ul>
            </div>
        </div>
    );
}

export default ResetPassword;