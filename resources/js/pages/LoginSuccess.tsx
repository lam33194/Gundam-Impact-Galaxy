import { useEffect, useState } from "react";
import { useLocation, useNavigate } from "react-router-dom";
import { toast } from "react-toastify";
import { fetchCurrentUser } from "../services/AuthService";
import { useAuth } from "../context/AuthContext";

export const LoginSuccess = () => {
    const location = useLocation();
    const navigate = useNavigate();
    const { login } = useAuth();

    const getCurrentUser = async () => {
        try {
            const token = new URLSearchParams(location.search).get("token");
            if (token) {
                localStorage.setItem("token", token);
                const res = await fetchCurrentUser();
                if (res && res.data) {
                    const user = {
                        id: res.data.data.id,
                        name: res.data.data.name,
                        email: res.data.data.email,
                        role: res.data.data.role,
                    };
                    login(user, token);
                    toast.success("Đăng nhập google thành công!");
                    navigate("/");
                }
            }
           
        } catch (error) {}
    };

    useEffect(() => {
        getCurrentUser();
    }, [location, navigate]);
    return <></>;
};
