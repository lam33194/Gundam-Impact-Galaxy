import { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import './Profile.scss';

function Profile() {
    const { user } = useAuth();
    const [isEditing, setIsEditing] = useState(false);
    const [formData, setFormData] = useState({
        name: user?.name || '',
        email: user?.email || '',
        phone: user?.phone || '',
        address: user?.address || ''
    });

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { id, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [id]: value
        }));
    };

    const handleUpdate = () => {
        setIsEditing(true);
    };

    const handleSave = () => {
        // TODO: Call API to update user info
        setIsEditing(false);
    };

    const handleCancel = () => {
        // Reset form data to original values
        setFormData({
            name: user?.name || '',
            email: user?.email || '',
            phone: user?.phone || '',
            address: user?.address || ''
        });
        setIsEditing(false);
    };

    return (
        <div className="container py-5">
            <div className="row g-4">
                <div className="col-md-4">
                    <div className="card shadow-sm h-100 profile-card">
                        <div className="card-body">
                            <h5 className="card-title mb-4">Ảnh đại diện</h5>
                            <div className="text-center">
                                <div className="avatar-container">
                                    <div className="avatar-wrapper">
                                        <img
                                            src={user?.avatar || "../assets/default-avatar.png"}
                                            alt="User Avatar"
                                            className="profile-avatar"
                                        />
                                    </div>
                                    {isEditing && (
                                        <button
                                            className="btn btn-light btn-sm position-absolute avatar-upload-btn shadow-sm"
                                        >
                                            <i className="fas fa-camera"></i>
                                        </button>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Personal Info Card */}
                <div className="col-md-8">
                    <div className="card shadow-sm profile-card">
                        <div className="card-body p-4">
                            <h5 className="card-title mb-4">Thông tin cá nhân</h5>
                            <form>
                                <div className="mb-3">
                                    <label htmlFor="name" className="form-label">Họ tên</label>
                                    <input
                                        type="text"
                                        className="form-control form-control-lg rounded-3"
                                        id="name"
                                        value={formData.name}
                                        onChange={handleChange}
                                        disabled={!isEditing}
                                    />
                                </div>

                                <div className="mb-3">
                                    <label htmlFor="email" className="form-label">Email</label>
                                    <input
                                        type="email"
                                        className="form-control form-control-lg rounded-3"
                                        id="email"
                                        value={formData.email}
                                        onChange={handleChange}
                                        disabled={!isEditing}
                                    />
                                </div>

                                <div className="mb-3">
                                    <label htmlFor="phone" className="form-label">Số điện thoại</label>
                                    <input
                                        type="tel"
                                        className="form-control form-control-lg rounded-3"
                                        id="phone"
                                        value={formData.phone}
                                        onChange={handleChange}
                                        disabled={!isEditing}
                                    />
                                </div>

                                <div className="mb-3">
                                    <label htmlFor="address" className="form-label">Địa chỉ</label>
                                    <input
                                        type="text"
                                        className="form-control form-control-lg rounded-3"
                                        id="address"
                                        value={formData.address}
                                        onChange={handleChange}
                                        disabled={!isEditing}
                                    />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div className="action-buttons">
                {!isEditing ? (
                    <button
                        type="button"
                        className="btn btn-primary btn-lg rounded-3 px-4"
                        onClick={handleUpdate}
                    >
                        Cập nhật thông tin
                    </button>
                ) : (
                    <div className="d-flex gap-2 justify-content-end">
                        <button
                            type="button"
                            className="btn btn-success btn-lg rounded-3 px-4"
                            onClick={handleSave}
                        >
                            Lưu cập nhật
                        </button>
                        <button
                            type="button"
                            className="btn btn-secondary btn-lg rounded-3 px-4"
                            onClick={handleCancel}
                        >
                            Huỷ bỏ
                        </button>
                    </div>
                )}
            </div>
        </div>
    );
}

export default Profile;