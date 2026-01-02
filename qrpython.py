import qrcode
from PIL import Image

def generate_qr_code(text, filename='qrcode.png', size=50, border=4):
    """
    Generate a larger QR code from text and save it as an image
    
    Args:
        text: The text or URL to encode in the QR code
        filename: The output filename (default: qrcode.png)
        size: Box size in pixels (default: 50, increase for bigger QR)
        border: Border size in boxes (default: 4)
    """
    # Create QR code instance
    qr = qrcode.QRCode(
        version=None,
        error_correction=qrcode.constants.ERROR_CORRECT_H,
        box_size=size,
        border=border,
    )
    
    # Add data
    qr.add_data(text)
    qr.make(fit=True)
    
    # Create image
    img = qr.make_image(fill_color="black", back_color="white")
    
    # Save image
    img.save(filename)
    print(f"QR code saved as {filename}")
    print(f"Image size: {img.size}")
    
    return img

# Example usage
if __name__ == "__main__":

    generate_qr_code("MEMBER001", "qrcode_member1.png", size=100)
    