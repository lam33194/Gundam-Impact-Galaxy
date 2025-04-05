import { createContext, useContext, useState, useEffect, ReactNode } from 'react';
import { useNavigate } from 'react-router-dom';

interface User {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    avatar: string | null;
    address: string | null;
    role: string;
}

interface AuthContextType {
    user: User | null;
    isAuthenticated: boolean;
    login: (userData: User, token: string) => void;
    logout: () => void;
}

const AuthContext = createContext<AuthContextType | null>(null);

export const AuthProvider = ({ children }: { children: ReactNode }) => {
    const [user, setUser] = useState<User | null>(null);
    const [isAuthenticated, setIsAuthenticated] = useState(false);
    const navigate = useNavigate();

    useEffect(() => {
        // Check if user is logged in on component mount
        const userInfo = localStorage.getItem('userInfo');
        const token = localStorage.getItem('token');

        if (userInfo && token) {
            setUser(JSON.parse(userInfo));
            setIsAuthenticated(true);
        }
    }, []);

    const login = (userData: User, token: string) => {
        setUser(userData);
        setIsAuthenticated(true);
        localStorage.setItem('userInfo', JSON.stringify(userData));
        localStorage.setItem('token', token);
    };

    const logout = () => {
        setUser(null);
        setIsAuthenticated(false);
        localStorage.removeItem('userInfo');
        localStorage.removeItem('token');
        navigate('/');
    };

    return (
        <AuthContext.Provider value={{ user, isAuthenticated, login, logout }
        }>
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = () => {
    const context = useContext(AuthContext);
    if (!context) {
        throw new Error('useAuth must be used within an AuthProvider');
    }
    return context;
};