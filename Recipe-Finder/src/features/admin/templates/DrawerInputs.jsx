import { Form, Input } from "antd";
import '../scss/DrawerInputs.scss';

export default function DrawerInput({
  icon,
  header,
  information,
  children,
  isEditing,
  value,
  onValueChange
}) {
  return (
    <Form layout="vertical">
      <div className="content">
        <div className="content-icon">{icon}</div>

        <div className="content-data">
          <h5>{header}</h5>
          {isEditing ? (
            <Input
              value={value !== undefined ? value : information}
              onChange={(e) => onValueChange && onValueChange(e.target.value)}
              type={header.toLowerCase().includes('password') ? 'password' : 'text'}
            />
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