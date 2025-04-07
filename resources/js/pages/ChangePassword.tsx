import { useState } from 'react';
import { changePassword } from '../services/AuthService';
import './ChangePassword.scss';
import { toast } from 'react-toastify';

function ChangePassword() {
    const [formData, setFormData] = useState({
        current_password: '',
        password: '',
        password_confirmation: ''
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
            if (!formData.current_password.trim()) {
                toast.error('Vui lòng nhập mật khẩu hiện tại');
                return;
            }

            if (!formData.password.trim()) {
                toast.error('Vui lòng nhập mật khẩu mới');
                return;
            }

            if (formData.password !== formData.password_confirmation) {
                toast.error('Mật khẩu mới không khớp');
                return;
            }

            await changePassword(formData);
            toast.success('Đổi mật khẩu thành công!');
            setFormData({
                current_password: '',
                password: '',
                password_confirmation: ''
            });
        } catch (error: any) {
            const errorMessage = error.response?.data?.message || 'Đổi mật khẩu thất bại. Vui lòng thử lại.';
            toast.error(errorMessage);
        }
    };

    return (
        <div className="container py-5">
            <div className="row justify-content-center">
                <div className="col-md-8">
                    <div className="card shadow-sm profile-card">
                        <div className="card-body p-4">
                            <h4 className="card-title mb-4">Đổi mật khẩu</h4>
                            <form onSubmit={handleSubmit}>
                                <div className="mb-3">
                                    <label htmlFor="current_password" className="form-label">
                                        Mật khẩu hiện tại
                                    </label>
                                    <input
                                        type="password"
                                        className="form-control rounded-3"
                                        id="current_password"
                                        value={formData.current_password}
                                        onChange={handleChange}
                                        autoComplete="current-password"
                                    />
                                </div>

                                <div className="mb-3">
                                    <label htmlFor="password" className="form-label">
                                        Mật khẩu mới
                                    </label>
                                    <input
                                        type="password"
                                        className="form-control rounded-3"
                                        id="password"
                                        value={formData.password}
                                        onChange={handleChange}
                                        autoComplete="new-password"
                                    />
                                </div>

                                <div className="mb-4">
                                    <label htmlFor="password_confirmation" className="form-label">
                                        Nhập lại mật khẩu mới
                                    </label>
                                    <input
                                        type="password"
                                        className="form-control rounded-3"
                                        id="password_confirmation"
                                        value={formData.password_confirmation}
                                        onChange={handleChange}
                                        autoComplete="new-password"
                                    />
                                </div>

                                <div className="d-flex justify-content-center">
                                    <button type="submit" className="btn btn-primary btn-lg rounded-3 px-4">
                                        Đổi mật khẩu
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default ChangePassword;