import { useState, useEffect } from 'react';
import { useAuth } from '../context/AuthContext';
import { getUserById, updateUser } from '../services/UserService';
import { getProvinces, getDistricts, getWards } from '../services/LocationService';
import { Province, District, Ward } from '../interfaces/Location';
import './Profile.scss';
import { UserData } from '../interfaces/UserData';
import InfoField from '../components/InfoField';
import { STORAGE_URL } from '../utils/constants';

function Profile() {
    const { user: authUser } = useAuth();
    const [isEditing, setIsEditing] = useState(false);
    const [isLoading, setIsLoading] = useState(true);
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        phone: '',
        address: '',
        ward: '',
        district: '',
        city: '',
        avatar: '',
        created_at: '',
        updated_at: ''
    });

    const [provinces, setProvinces] = useState<Province[]>([]);
    const [districts, setDistricts] = useState<District[]>([]);
    const [wards, setWards] = useState<Ward[]>([]);

    const [selectedProvince, setSelectedProvince] = useState<string>('');
    const [selectedDistrict, setSelectedDistrict] = useState<string>('');
    const [selectedWard, setSelectedWard] = useState<string>('');

    const DateDisplay: React.FC<{ label: string; icon: string; date: string }> = ({ label, icon, date }) => (
        <div className="mb-2">
            <i className={`fas ${icon} me-2`}></i>
            {label}: {new Date(date).toLocaleDateString('vi-VN', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            })}
        </div>
    );

    const setUserFormData = (userData: UserData, userAddress: any) => {
        return {
            name: userData.name || '',
            email: userData.email || '',
            phone: userData.phone || '',
            address: userAddress?.address || '',
            avatar: userData.avatar || '',
            created_at: userData.created_at || '',
            updated_at: userData.updated_at || '',
            ward: userAddress?.ward || '',
            district: userAddress?.district || '',
            city: userAddress?.city || ''
        };
    };

    const loadLocationData = async (city: string, district: string, ward: string) => {
        const provincesResponse = await getProvinces();
        setProvinces(provincesResponse.data);

        if (city) {
            const matchingProvince = provincesResponse.data.find(p => p.name === city);
            if (matchingProvince) {
                setSelectedProvince(matchingProvince.id);

                const districtsResponse = await getDistricts(matchingProvince.id);
                setDistricts(districtsResponse.data);

                if (district) {
                    const matchingDistrict = districtsResponse.data.find(d => d.name === district);
                    if (matchingDistrict) {
                        setSelectedDistrict(matchingDistrict.id);

                        const wardsResponse = await getWards(matchingDistrict.id);
                        setWards(wardsResponse.data);

                        if (ward) {
                            const matchingWard = wardsResponse.data.find(w => w.name === ward);
                            if (matchingWard) {
                                setSelectedWard(matchingWard.id);
                            }
                        }
                    }
                }
            }
        }
    };

    useEffect(() => {
        const fetchUserData = async () => {
            try {
                if (!authUser?.id) return;

                setIsLoading(true);
                const response = await getUserById(authUser.id, { include: 'addresses' });
                const userData = response.data.data[0] as UserData;
                const userAddress = userData.addresses?.[0];

                setFormData(setUserFormData(userData, userAddress));

                await loadLocationData(
                    userAddress?.city || '',
                    userAddress?.district || '',
                    userAddress?.ward || ''
                );
            } catch (error) {
                console.error('Failed to fetch user data:', error);
            } finally {
                setIsLoading(false);
            }
        };

        fetchUserData();
    }, [authUser?.id]);

    useEffect(() => {
        if (selectedProvince) {
            const fetchDistricts = async () => {
                try {
                    setSelectedWard('');
                    setWards([]);

                    setSelectedDistrict('');
                    setDistricts([]);

                    setFormData(prev => ({
                        ...prev,
                        ward: '',
                        district: '',
                        city: provinces.find(p => p.id === selectedProvince)?.name || ''
                    }));

                    const response = await getDistricts(selectedProvince);
                    setDistricts(response.data);

                } catch (error) {
                    console.error('Failed to fetch districts:', error);
                }
            };
            fetchDistricts();
        } else {
            setSelectedDistrict('');
            setDistricts([]);
            setSelectedWard('');
            setWards([]);

            setFormData(prev => ({
                ...prev,
                ward: '',
                district: '',
                city: ''
            }));
        }
    }, [selectedProvince]);

    useEffect(() => {
        if (selectedDistrict) {
            const fetchWards = async () => {
                try {
                    const response = await getWards(selectedDistrict);
                    setWards(response.data);

                    if (formData.ward) {
                        const matchingWard = response.data.find(
                            w => w.name === formData.ward
                        );
                        if (matchingWard) {
                            setSelectedWard(matchingWard.id);
                        }
                    }
                } catch (error) {
                    console.error('Failed to fetch wards:', error);
                }
            };
            fetchWards();
        }
    }, [selectedDistrict, formData.ward]);

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

    const handleSave = async () => {
        try {
            if (!authUser?.id) return;

            if (!formData.name.trim()) {
                alert('Vui lòng nhập họ tên');
                return;
            }

            if (!formData.phone.trim()) {
                alert('Vui lòng nhập số điện thoại');
                return;
            }

            const updateData = {
                name: formData.name,
                phone: formData.phone,
                address: formData.address,
                ward: wards.find(w => w.id === selectedWard)?.name || '',
                district: districts.find(d => d.id === selectedDistrict)?.name || '',
                city: provinces.find(p => p.id === selectedProvince)?.name || ''
            };

            await updateUser(updateData);

            const response = await getUserById(authUser.id, { include: 'addresses' });
            const userData = response.data.data[0] as UserData;
            const userAddress = userData.addresses?.[0];

            setFormData(setUserFormData(userData, userAddress));
            setIsEditing(false);
            alert('Cập nhật thông tin thành công!');
        } catch (error) {
            console.error('Failed to update user data:', error);
            alert('Cập nhật thông tin thất bại. Vui lòng thử lại.');
        }
    };

    const handleCancel = async () => {
        if (!authUser?.id) return;

        try {
            setSelectedProvince('');
            setSelectedDistrict('');
            setSelectedWard('');
            setDistricts([]);
            setWards([]);

            const response = await getUserById(authUser.id, { include: 'addresses' });
            const userData = response.data.data[0] as UserData;
            const userAddress = userData.addresses?.[0];

            setFormData(setUserFormData(userData, userAddress));

            if (userAddress) {
                await loadLocationData(
                    userAddress.city || '',
                    userAddress.district || '',
                    userAddress.ward || ''
                );
            }
        } catch (error) {
            console.error('Failed to reset user data:', error);
        }
        setIsEditing(false);
    };

    const getAvatarUrl = (avatarPath: string | null) => {
        if (!avatarPath) return "../assets/default-avatar.png";
        return `${STORAGE_URL}/${avatarPath}`;
    };

    const renderAddressSection = () => (
        <div className="address-section mb-4">
            <label className="form-label">Thông tin địa chỉ</label>
            <div className="row g-3 mb-3">
                <div className="col-md-4">
                    <select
                        className="form-select"
                        value={selectedProvince}
                        onChange={(e) => setSelectedProvince(e.target.value)}
                        disabled={!isEditing}
                    >
                        <option value="">Chọn Tỉnh/Thành phố</option>
                        {provinces.map(province => (
                            <option key={province.id} value={province.id}>
                                {province.name}
                            </option>
                        ))}
                    </select>
                </div>
                <div className="col-md-4">
                    <select
                        className="form-select"
                        value={selectedDistrict}
                        onChange={(e) => setSelectedDistrict(e.target.value)}
                        disabled={!isEditing || !selectedProvince}
                    >
                        <option value="">Chọn Quận/Huyện</option>
                        {districts.map(district => (
                            <option key={district.id} value={district.id}>
                                {district.name}
                            </option>
                        ))}
                    </select>
                </div>
                <div className="col-md-4">
                    <select
                        className="form-select"
                        value={selectedWard}
                        onChange={(e) => setSelectedWard(e.target.value)}
                        disabled={!isEditing || !selectedDistrict}
                    >
                        <option value="">Chọn Xã/Phường</option>
                        {wards.map(ward => (
                            <option key={ward.id} value={ward.id}>
                                {ward.name}
                            </option>
                        ))}
                    </select>
                </div>
            </div>
            <div className="mb-3">
                <input
                    type="text"
                    className="form-control rounded-3"
                    id="address"
                    placeholder="Nhập địa chỉ đầy đủ"
                    value={formData.address}
                    onChange={handleChange}
                    disabled={!isEditing}
                    autoComplete="off"
                    key="address"
                />
            </div>
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
                                            src={getAvatarUrl(formData.avatar)}
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
                            <div className="mt-4 pt-3 border-top">
                                <div className="text-muted small">
                                    <DateDisplay
                                        label="Ngày tạo"
                                        icon="fa-clock"
                                        date={formData.created_at}
                                    />
                                    <DateDisplay
                                        label="Cập nhật lần cuối"
                                        icon="fa-edit"
                                        date={formData.updated_at}
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="col-md-8">
                    <div className="card shadow-sm profile-card">
                        <div className="card-body p-4">
                            <h5 className="card-title mb-4">Thông tin cá nhân</h5>
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
                                {renderAddressSection()}
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