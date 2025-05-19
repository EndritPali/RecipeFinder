import DashboardHeader from "../Components/DashboardHeader";
import { DashboardOutlined } from '@ant-design/icons';
import '../scss/Dashboard.scss';
import { useState } from "react";
import DashboardSider from "../Components/DashboardSider";
import { Outlet } from "react-router-dom";
import ResponsiveDrawer from "../Templates/ResponsiveDrawer";
import { Button } from "antd";

export default function Dashboard() {
  const [isDrawerOpen, setIsDrawerOpen] = useState(false);
  const showDrawer = () => setIsDrawerOpen(true);
  const closeDrawer = () => setIsDrawerOpen(false);

  return (
    <>
      <DashboardHeader />
      <div className="content-area">
        <Button
          icon={<DashboardOutlined />}
          className="drawer-activate"
          onClick={showDrawer} />
        <DashboardSider />
        <ResponsiveDrawer open={isDrawerOpen} onClose={closeDrawer} />
        <Outlet />
      </div>
    </>
  )
}