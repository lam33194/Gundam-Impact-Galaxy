import { useState, useEffect, useRef } from 'react';
import { useAuth } from '../context/AuthContext';
import { getUserById, updateUser } from '../services/UserService';
import './Profile.scss';
import { UserData } from '../interfaces/UserData';
import InfoField from '../components/InfoField';
import { STORAGE_URL } from '../utils/constants';
import { toast } from 'react-toastify';
import { getAddresses, deleteAddress, setPrimaryAddress } from '../services/AddressService';
import type { Address } from '../services/AddressService';
import AddressModal from '../components/AddressModal';
import ConfirmModal from '../components/ConfirmModal';

function Profile() {
    const { user: authUser } = useAuth();
    const [isEditing, setIsEditing] = useState(false);
    const [isLoading, setIsLoading] = useState(true);
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        phone: '',
        avatar: '',
        created_at: '',
        updated_at: ''
    });

    const [avatarFile, setAvatarFile] = useState<File | null>(null);
    const [avatarPreview, setAvatarPreview] = useState<string | null>(null);
    const fileInputRef = useRef<HTMLInputElement>(null);
    const [activeTab, setActiveTab] = useState('personal');
    const [addresses, setAddresses] = useState<Address[]>([]);
    const [showAddressModal, setShowAddressModal] = useState(false);
    const [selectedAddress, setSelectedAddress] = useState<Address | undefined>(undefined);
    const [showDeleteConfirm, setShowDeleteConfirm] = useState(false);
    const [selectedDeleteId, setSelectedDeleteId] = useState<number | null>(null);
    const [isDeleting, setIsDeleting] = useState(false);

    const setUserFormData = (userData: UserData) => {
        return {
            name: userData.name || '',
            email: userData.email || '',
            phone: userData.phone || '',
            avatar: userData.avatar || '',
            created_at: userData.created_at || '',
            updated_at: userData.updated_at || ''
        };
    };

    useEffect(() => {
        const fetchUserData = async () => {
            try {
                if (!authUser?.id) return;

                setIsLoading(true);
                const response = await getUserById(authUser.id);
                const userData = response.data.data as UserData;
                setFormData(setUserFormData(userData));
            } catch (error) {
                console.error('Failed to fetch user data:', error);
            } finally {
                setIsLoading(false);
            }
        };

        fetchUserData();
    }, [authUser?.id]);

    useEffect(() => {
        if (activeTab === 'address') {
            const fetchAddresses = async () => {
                try {
                    const response = await getAddresses();
                    if (response.data?.data) {
                        setAddresses(response.data.data);
                    }
                } catch (error) {
                    console.error('Failed to fetch addresses:', error);
                    toast.error('Không thể tải danh sách địa chỉ');
                }
            };
            fetchAddresses();
        }
    }, [activeTab]);

    const handleTabChange = (tab: string) => {
        if (isEditing) {
            handleCancel();
        }
        setActiveTab(tab);
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { id, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [id]: value
        }));
    };

    const handleAvatarChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        const file = event.target.files?.[0];
        if (file) {
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                toast.error('Vui lòng chọn file ảnh có định dạng .jpg, .jpeg hoặc .png');
                return;
            }

            const previewUrl = URL.createObjectURL(file);
            setAvatarPreview(previewUrl);
            setAvatarFile(file);
        }
    };

    const handleUpdate = () => {
        setIsEditing(true);
    };

    const handleSave = async () => {
        try {
            if (!authUser?.id) return;

            if (!formData.name.trim()) {
                toast.error('Vui lòng nhập họ tên');
                return;
            }

            if (!formData.phone.trim()) {
                toast.error('Vui lòng nhập số điện thoại');
                return;
            }

            const formDataToSend = new FormData();
            formDataToSend.append('name', formData.name);
            formDataToSend.append('phone', formData.phone);

            if (avatarFile) {
                formDataToSend.append('avatar', avatarFile);
            }

            await updateUser(formDataToSend);
            setAvatarFile(null);
            setAvatarPreview(null);

            const response = await getUserById(authUser.id);
            const userData = response.data.data as UserData;
            setFormData(setUserFormData(userData));
            setIsEditing(false);
            toast.success('Cập nhật thông tin thành công!');
        } catch (error: any) {
            console.error('Failed to update user data:', error);
            const errorMessage = error.response?.data?.message || 'Cập nhật thông tin thất bại. Vui lòng thử lại.';
            toast.error(errorMessage);
        }
    };

    const handleCancel = async () => {
        if (!authUser?.id) return;

        try {
            setAvatarFile(null);
            setAvatarPreview(null);
            if (fileInputRef.current) {
                fileInputRef.current.value = '';
            }

            const response = await getUserById(authUser.id);
            const userData = response.data.data as UserData;
            setFormData(setUserFormData(userData));
        } catch (error) {
            console.error('Failed to reset user data:', error);
        }
        setIsEditing(false);
    };

    const handleEditAddress = (address: Address) => {
        setSelectedAddress(address);
        setShowAddressModal(true);
    };

    const handleAddNewAddress = () => {
        setSelectedAddress(undefined);
        setShowAddressModal(true);
    };

    const handleAddressModalSuccess = () => {
        const fetchAddresses = async () => {
            try {
                const response = await getAddresses();
                if (response.data?.data) {
                    setAddresses(response.data.data);
                }
            } catch (error) {
                console.error('Failed to fetch addresses:', error);
                toast.error('Không thể tải danh sách địa chỉ');
            }
        };
        fetchAddresses();
    };

    const handleDeleteClick = (id: number) => {
        setSelectedDeleteId(id);
        setShowDeleteConfirm(true);
    };

    const handleDeleteConfirm = async () => {
        if (!selectedDeleteId) return;

        setIsDeleting(true);
        try {
            await deleteAddress(selectedDeleteId);
            toast.success('Xoá địa chỉ thành công');
            const response = await getAddresses();
            if (response.data?.data) {
                setAddresses(response.data.data);
            }
        } catch (error: any) {
            console.error('Failed to delete address:', error);
            const errorMessage = error.response?.data?.message || 'Không thể xoá địa chỉ. Vui lòng thử lại.';
            toast.error(errorMessage);
        } finally {
            setIsDeleting(false);
            setShowDeleteConfirm(false);
            setSelectedDeleteId(null);
        }
    };

    const getAvatarUrl = (avatarPath: string | null) => {
        if (!avatarPath) return "../assets/default-avatar.png";
        return `${STORAGE_URL}/${avatarPath}`;
    };

    const renderAddressSection = () => (
        <div className="address-section">
            <div className="d-flex justify-content-between align-items-center mb-3">
                <h5 className="mb-0">Địa chỉ của tôi</h5>
                <button
                    className="btn btn-outline-primary btn-sm"
                    onClick={handleAddNewAddress}
                >
                    <i className="fas fa-plus me-2"></i>
                    Thêm địa chỉ mới
                </button>
            </div>

            <div className="addresses-list d-flex flex-column gap-2">
                {addresses.map((address) => (
                    <div
                        key={address.id}
                        className={`address-item py-2 px-3 rounded-3 border position-relative ${address.is_primary ? 'border-primary' : ''
                            }`}
                    >
                        <div className="address-content d-flex flex-column justify-content-center">
                            <p className="address-text mb-1">
                                <span className="text-muted me-1 fw-bold">Địa chỉ:</span>
                                {address.address}, {address.ward}, {address.district}, {address.city}
                            </p>
                            {address.is_primary === 1 && (
                                <span className="badge bg-primary rounded-pill">
                                    Mặc định
                                </span>
                            )}
                        </div>
                        <div className="action-buttons position-absolute top-50 end-0 translate-middle-y me-3 d-flex gap-2">
                            <button
                                className="btn btn-link text-primary p-1"
                                disabled={address.is_primary === 1}
                                title="Đặt làm địa chỉ mặc định"
                            >
                                <i className="fas fa-star"></i>
                            </button>
                            <button
                                className="btn btn-link text-danger p-1"
                                onClick={() => handleDeleteClick(address.id)}
                                disabled={address.is_primary === 1}
                                title="Xoá địa chỉ"
                            >
                                <i className="fas fa-trash-alt"></i>
                            </button>
                            <button
                                className="btn btn-link text-dark p-1"
                                onClick={() => handleEditAddress(address)}
                                title="Chỉnh sửa địa chỉ"
                            >
                                <i className="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                ))}
            </div>

            <ConfirmModal
                show={showDeleteConfirm}
                onHide={() => setShowDeleteConfirm(false)}
                onConfirm={handleDeleteConfirm}
                title="Xác nhận xoá"
                message="Bạn có chắc chắn muốn xoá địa chỉ này?"
                isLoading={isDeleting}
                confirmText="Xoá"
                loadingText="Đang xoá..."
                confirmVariant="danger"
            />
        </div>
    );

    if (isLoading) {
        return (
            <div className="container py-5 text-center">
                <div className="spinner-border" role="status">
                    <span className="visually-hidden">Loading...</span>
                </div>
            </div>
        );
    }

    return (
        <>
            <div className="container pt-5">
                <div className="row g-4">
                    <div className="col-md-4">
                        <div className="card shadow-sm h-100 profile-card">
                            <div className="card-body">
                                <h5 className="card-title mb-4">Ảnh đại diện</h5>
                                <div className="text-center">
                                    <div className="avatar-container">
                                        <div className="avatar-wrapper">
                                            <label htmlFor="avatar-input" style={{ cursor: isEditing ? 'pointer' : 'default' }}>
                                                <img
                                                    src={avatarPreview || getAvatarUrl(formData.avatar)}
                                                    alt="User Avatar"
                                                    className="profile-avatar"
                                                />
                                            </label>
                                            <input
                                                ref={fileInputRef}
                                                type="file"
                                                id="avatar-input"
                                                className="d-none"
                                                accept="image/png,image/jpeg,image/jpg"
                                                onChange={handleAvatarChange}
                                                disabled={!isEditing}
                                            />
                                        </div>
                                        {isEditing && (
                                            <label
                                                htmlFor="avatar-input"
                                                className="btn btn-light btn-sm position-absolute avatar-upload-btn shadow-sm"
                                                style={{ cursor: 'pointer' }}
                                            >
                                                <i className="fas fa-camera"></i>
                                            </label>
                                        )}
                                    </div>
                                </div>
                                <div className="mt-4 pt-3 border-top">
                                    <div className="text-muted small">
                                        <div className="mb-2">
                                            <i className="fas fa-clock me-2"></i>
                                            Ngày tạo: {new Date(formData.created_at).toLocaleDateString('vi-VN', {
                                                day: '2-digit',
                                                month: '2-digit',
                                                year: 'numeric',
                                                hour: '2-digit',
                                                minute: '2-digit'
                                            })}
                                        </div>
                                        <div className="mb-2">
                                            <i className="fas fa-edit me-2"></i>
                                            Cập nhật lần cuối: {new Date(formData.updated_at).toLocaleDateString('vi-VN', {
                                                day: '2-digit',
                                                month: '2-digit',
                                                year: 'numeric',
                                                hour: '2-digit',
                                                minute: '2-digit'
                                            })}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="col-md-8">
                        <div className="card shadow-sm profile-card">
                            <div className="card-body p-4">
                                <ul className="nav nav-tabs mb-4">
                                    <li className="nav-item">
                                        <button
                                            className={`nav-link ${activeTab === 'personal' ? 'active' : ''}`}
                                            onClick={() => handleTabChange('personal')}
                                        >
                                            Thông tin cá nhân
                                        </button>
                                    </li>
                                    <li className="nav-item">
                                        <button
                                            className={`nav-link ${activeTab === 'address' ? 'active' : ''}`}
                                            onClick={() => handleTabChange('address')}
                                        >
                                            Thông tin địa chỉ
                                        </button>
                                    </li>
                                </ul>

                                {activeTab === 'personal' ? (
                                    <form>
                                        <InfoField
                                            label="Email"
                                            id="email"
                                            type="email"
                                            value={formData.email}
                                            onChange={handleChange}
                                            disabled
                                        />
                                        <InfoField
                                            label="Họ tên"
                                            id="name"
                                            value={formData.name}
                                            onChange={handleChange}
                                            disabled={!isEditing}
                                        />
                                        <InfoField
                                            label="Số điện thoại"
                                            id="phone"
                                            type="tel"
                                            value={formData.phone}
                                            onChange={handleChange}
                                            disabled={!isEditing}
                                        />
                                        <div className="mt-4">
                                            {!isEditing ? (
                                                <button
                                                    type="button"
                                                    className="btn btn-primary px-4"
                                                    onClick={handleUpdate}
                                                >
                                                    Cập nhật thông tin
                                                </button>
                                            ) : (
                                                <div className="d-flex gap-2">
                                                    <button
                                                        type="button"
                                                        className="btn btn-success px-4"
                                                        onClick={handleSave}
                                                    >
                                                        Lưu cập nhật
                                                    </button>
                                                    <button
                                                        type="button"
                                                        className="btn btn-secondary px-4"
                                                        onClick={handleCancel}
                                                    >
                                                        Huỷ bỏ
                                                    </button>
                                                </div>
                                            )}
                                        </div>
                                    </form>
                                ) : (
                                    renderAddressSection()
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <AddressModal
                show={showAddressModal}
                onHide={() => setShowAddressModal(false)}
                address={selectedAddress}
                onSuccess={handleAddressModalSuccess}
            />
        </>
    );
}

export default Profile;