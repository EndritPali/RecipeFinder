import { Form, Input } from "antd";
import { useState } from "react";
import '../scss/DrawerInputs.scss';

export default function DrawerInput({ icon, header, information, children, isEditing }) {
  const [value, setValue] = useState(information);

  return (
    <Form layout="vertical">
      <div className="content">
        <div className="content-icon">{icon}</div>

        <div className="content-data">
          <h5>{header}</h5>
          {isEditing ? (
            <Input value={value} onChange={(e) => setValue(e.target.value)} />
          ) : (
            <p>{information}</p>
          )}
        </div>
      </div>

      {children && (
        <div className="content-action-buttons">
          {children}
        </div>
      )}
    </Form>
  );
}
