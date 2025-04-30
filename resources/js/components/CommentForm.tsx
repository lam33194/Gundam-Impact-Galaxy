import React, { useState, useEffect } from 'react';
import { STORAGE_URL } from '../utils/constants';

const CommentForm = ({ isOpen, onCloseForm, comment, onUpdateComment } : any) => {
  const [rating, setRating] = useState(0);
  const [hoverRating, setHoverRating] = useState(0);
  const [content, setContent] = useState('');
  const [images, setImages] = useState<any>([]);
  const [imagePreviews, setImagePreviews] = useState<any>([]);

  const urlToFile = async (url: string, filename: string, mimeType: string) => {
    const res = await fetch(url);
    const blob = await res.blob();
    return new File([blob], filename, { type: mimeType });
  };


  useEffect(() => {
    if (isOpen && comment) {
      (async () => {
        const urls = comment.comment_images.map((img: any) => STORAGE_URL + "/" + img.image);
        const filePromises = urls.map((url: any, idx: any) => {
          const segments = url.split('/');
          const filename = segments[segments.length - 1] || `image-${idx}.jpg`;
          const mimeType = 'image/jpeg';
          return urlToFile(url, filename, mimeType);
        });
        const files: File[] = await Promise.all(filePromises);
        setImages(files);
        setImagePreviews(files.map(file => URL.createObjectURL(file)));
        setRating(comment.rating || 0);
        setContent(comment.content || '');
      })();
      // setImages(comment.comment_images.map((image:any) => image.image) || []);
      // setImagePreviews(comment.comment_images.map((image:any) => STORAGE_URL + "/" + image.image) || []);
    } else {
      setRating(0);
      setContent('');
      setImages([]);
      setImagePreviews([]);
    }
  }, [isOpen, comment]);

  const handleImageUpload = (e: any) => {
    const files = Array.from(e.target.files);
    if (files.length + images.length > 5) {
        alert("Tối đa 5 ảnh được phép tải lên");
        return;
    }

    const newImages = [...images, ...files];
    setImages(newImages.slice(0, 5));

    // Tạo preview
    const previews = files.map((file: any) => URL.createObjectURL(file));
    setImagePreviews([...imagePreviews, ...previews].slice(0, 5));
};

const removeImage = (index: any) => {
    const newImages = [...images];
    newImages.splice(index, 1);
    setImages(newImages);

    const newPreviews = [...imagePreviews];
    URL.revokeObjectURL(newPreviews[index]);
    newPreviews.splice(index, 1);
    setImagePreviews(newPreviews);
};
  const handleSubmitComment = (event: any) => {
    event.preventDefault();
    onUpdateComment(content, rating, images);
    onCloseForm(); 
  };

  return (
    <div className={`modal fade ${isOpen ? 'show' : ''}`} style={{ display: isOpen ? 'block' : 'none' }} aria-hidden={!isOpen}>
      <div className="modal-dialog">
        <div className="modal-content border-0 rounded-3 shadow-lg">
          <div className="modal-header bg-light">
            <h5 className="modal-title text-primary">Gửi bình luận</h5>
            <button type="button" className="btn-close" onClick={onCloseForm} aria-label="Đóng"></button>
          </div>
          <div className="modal-body">
            <form onSubmit={handleSubmitComment} className="mt-3">
              <div className="form-group mb-3">
                <div className="star-rating text-warning">
                  {[1, 2, 3, 4, 5].map((star) => (
                    <button
                      key={star}
                      type="button"
                      className="btn p-1"
                      onClick={() => setRating(star)}
                      onMouseEnter={() => setHoverRating(star)}
                      onMouseLeave={() => setHoverRating(0)}
                    >
                      <i
                        className="fa-solid fa-star"
                        style={{
                          color: star <= (hoverRating || rating) ? "#ffc107" : "#ddd",
                          fontSize: '1.5rem', 
                        }}
                      ></i>
                    </button>
                  ))}
                </div>
              </div>


              <div className="form-group mb-3">
                <textarea
                  rows={4}
                  value={content}
                  onChange={(e) => setContent(e.target.value)}
                  placeholder="Nội dung bình luận"
                  className="form-control border border-secondary rounded"
                  style={{ resize: 'none' }} 
                />
              </div>

              <div className="form-group mb-3">
                <label className="text-muted">Tải lên ảnh (tối đa 5 ảnh)</label>
                <div className="d-flex flex-wrap gap-2 mb-2">
                  {imagePreviews.map((preview, index) => (
                    <div key={index} className="position-relative" style={{ width: "120px", height: "120px" }}>
                      <img
                        src={preview}
                        alt={`Preview ${index}`}
                        className="img-thumbnail border-0 h-100 w-100 object-fit-cover"
                      />
                      <button
                        type="button"
                        className="btn btn-sm btn-danger position-absolute top-0 end-0"
                        onClick={() => removeImage(index)}
                      >
                        <i className="fa-solid fa-xmark"></i>
                      </button>
                    </div>
                  ))}
                </div>

                <label className="btn btn-outline-secondary">
                  Chọn ảnh
                  <input
                    type="file"
                    multiple
                    accept="image/*"
                    onChange={handleImageUpload}
                    className="d-none"
                  />
                </label>
                <small className="d-block text-muted">
                  Đã chọn: {images.length}/5 ảnh
                </small>
              </div>

              <button
                type="submit"
                className="btn btn-primary"
                disabled={!content && !rating && images.length === 0}
              >
                Gửi bình luận
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  );
};
export default CommentForm;
