import { useState } from "react";
import "./ForgetPassword.scss";

function ForgetPassword() {
    const [email, setEmail] = useState("");

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        // TODO: Implement forget password logic
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
                                type="email"
                                id="email"
                                className="form-control form-control-lg"
                                placeholder="Nhập email của bạn"
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                required
                            />
                        </div>
                        <button type="submit" className="btn btn-lg btn-dark col-12">
                            Gửi yêu cầu
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