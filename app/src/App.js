import { Route, Routes } from 'react-router-dom';

import Home from './pages/Home/Home.js';
import Login from './pages/Login/Login.js';

export default function App() {
  return (
    <Routes>
      <Route path='/' element={<Home />} />
      <Route path='/login' element={<Login />} />
    </Routes>

  );
}