import { Outlet } from 'react-router-dom';
import Footer from '../layouts/Footer';
import Header from '../layouts/Header';
import './Layout.scss';

const Layout = () => {
  return (
    <div className="app">
      <Header />
      <main className="main-content my-5">
        <Outlet />
      </main>
      <Footer />
    </div>
  );
};

export default Layout;
