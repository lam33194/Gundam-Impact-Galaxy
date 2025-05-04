import { useLocation } from "react-router-dom";
import { useNavigate } from "react-router-dom";
import "./Login.scss";
import { useEffect, useState } from "react";
import { authenticate, loginByGoogle } from "../services/AuthService";
import { useAuth } from "../context/AuthContext";
import { toast } from "react-toastify";

interface LoginResponse {
  0: {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    avatar: string | null;
    phone: string | null;
    is_active: number;
    role: string;
    created_at: string;
    updated_at: string;
  };
  1: string;
}

const Login = () => {
  const navigate = useNavigate();
  const location = useLocation();
  const { login } = useAuth();

  const searchParams = new URLSearchParams(location.search);
  const prevUrl = searchParams.get('prevUrl');

  const [formData, setFormData] = useState({
    email: location.state?.email || "",
    password: location.state?.password || ""
  });

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { id, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [id]: value
    }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const response = await authenticate(formData);
      const data = response.data as LoginResponse;

      const redirectUrl = prevUrl ? decodeURIComponent(prevUrl) : '/';

      await login(data[0], data[1]);
      toast.success('Đăng nhập thành công');

      setTimeout(() => {
        navigate(redirectUrl);
      }, 100);

    } catch (error: any) {
      if (error.response?.status === 401) {
        toast.error(error.response.data.message);
      } else {
        toast.error('Có lỗi xảy ra, vui lòng thử lại sau');
      }
    }
  };

  const loginGoogle = async () => {
    try {
      const res = await loginByGoogle();
      if (res && res.data) {
        const redirectUrl = res.data.data;
        if (prevUrl) {
          window.location.href = `${redirectUrl}&state=${encodeURIComponent(prevUrl)}`;
        } else {
          window.location.href = redirectUrl;
        }
      }
    } catch (error) {
      console.error(error);
    }
  };

  useEffect(() => {
    if (location.state?.email && location.state?.password) {
      setFormData({
        email: location.state.email,
        password: location.state.password
      });
    }
  }, [location]);

  return (
    <div className="login-container d-flex m-auto row col-lg-8">
      <div className="left d-flex flex-column col-lg-8 p-5 gap-3">
        <form onSubmit={handleSubmit}>
          <div className="form-group">
            <h4 className="fw-bold mb-4">Đăng nhập tài khoản</h4>
            <div className="mb-3">
              <label htmlFor="email" className="form-label fw-bold">
                Email
              </label>
              <input
                type="email"
                id="email"
                className="form-control form-control-lg"
                placeholder="Email"
                value={formData.email}
                onChange={handleChange}
                required
              />
            </div>
            <div className="mb-3">
              <label htmlFor="password" className="form-label fw-bold">
                Mật khẩu
              </label>
              <input
                type="password"
                id="password"
                className="form-control form-control-lg"
                placeholder="Mật khẩu"
                value={formData.password}
                onChange={handleChange}
                required
              />
            </div>
            <button type="submit" className="btn btn-lg btn-dark col-12">
              Đăng nhập
            </button>
          </div>
        </form>
        <div className="d-flex align-items-center justify-content-center">
          <div className="line col-4"></div>
          <span className="col-4 text-center">Hoặc đăng nhập bằng</span>
          <div className="line col-4"></div>
        </div>

        <div className="row justify-content-center gap-2 oauth">
          <button className="btn btn-lg btn-primary btn-block col-md-3 col-sm-6 mb-2 d-flex align-items-center justify-content-center">
            <i className="fab fa-facebook-f me-2"></i> <span>Facebook</span>
          </button>

          <button onClick={loginGoogle} className="btn btn-lg btn-danger btn-block col-md-3 col-sm-6 mb-2 d-flex align-items-center justify-content-center">
            <i className="fab fa-google me-2"></i> <span>Google</span>
          </button>
        </div>
        <h6 className="text-center">
          Bạn quên mật khẩu nhấn <a href="/forget-password">vào đây</a>
        </h6>
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
        <button className="btn btn-lg col-7" onClick={() => navigate('/signup')}>Đăng ký</button>
      </div>
    </div>
  );
}

export default Login;