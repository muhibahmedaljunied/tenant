import cv2
from ultralytics import YOLO

# Load YOLOv8 model
model = YOLO('yolo11n.pt')

# Load video
video_path = 'assets/football.mp4'
cap = cv2.VideoCapture(video_path)

# Default bounding box settings
DEFAULT_WIDTH = 50
DEFAULT_HEIGHT = 80
resize_step = 10
alpha = 0.2  # Interpolation factor for smoother resizing

# Bounding Box Variables
drawing = False
dragging = False
tracker = cv2.TrackerCSRT_create()
tracking = False
current_bbox = None
start_drag_pos = None


def lerp(start, end, alpha):
    """ Linearly interpolates between start and end values """
    return int(start + alpha * (end - start))


def mouse_events(event, x, y, flags, param):
    global drawing, dragging, current_bbox, tracking, start_drag_pos

    if event == cv2.EVENT_LBUTTONDOWN:
        if current_bbox and (current_bbox[0] <= x <= current_bbox[0] + current_bbox[2]) and (
                current_bbox[1] <= y <= current_bbox[1] + current_bbox[3]):
            dragging = True
            start_drag_pos = (x - current_bbox[0], y - current_bbox[1])
        else:
            drawing = True
            current_bbox = [x, y, DEFAULT_WIDTH, DEFAULT_HEIGHT]
            tracking = False

    elif event == cv2.EVENT_MOUSEMOVE:
        if drawing:
            current_bbox[2] = max(DEFAULT_WIDTH, abs(current_bbox[0] - x))
            current_bbox[3] = max(DEFAULT_HEIGHT, abs(current_bbox[1] - y))
        elif dragging:
            current_bbox[0] = x - start_drag_pos[0]
            current_bbox[1] = y - start_drag_pos[1]

    elif event == cv2.EVENT_LBUTTONUP:
        drawing = False
        dragging = False
        start_drag_pos = None
        if current_bbox[2] > 20 and current_bbox[3] > 20:
            tracker.init(frame, tuple(current_bbox))
            tracking = True


def yolo_detection():
    global detections
    results = model.predict(source=frame, save=False, show=False)
    detections = results[0].boxes.data.cpu().numpy()


def draw_yolo_detections():
    global detections
    for det in detections:
        x1, y1, x2, y2, conf, cls = det[:6]
        cv2.rectangle(frame, (int(x1), int(y1)), (int(x2), int(y2)), (255, 0, 0), 2)
        cv2.putText(frame, f"{int(cls)}: {conf:.2f}", (int(x1), int(y1) - 10),
                    cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 0, 0), 2)


def default_tracking():
    global tracking, current_bbox, detections
    if not tracking and len(detections) > 0:
        x1, y1, x2, y2, conf, cls = detections[0][:6]
        object_center_x = (x1 + x2) // 2
        object_center_y = (y1 + y2) // 2

        if not current_bbox:
            current_bbox = [int(object_center_x - DEFAULT_WIDTH // 2),
                            int(object_center_y - DEFAULT_HEIGHT // 2),
                            DEFAULT_WIDTH, DEFAULT_HEIGHT]
            tracker.init(frame, tuple(current_bbox))
            tracking = True


def update_tracking():
    global tracking, current_bbox,key
    if tracking:
        success, box = tracker.update(frame)
        if success:
            # current_bbox = [int(v) for v in box]
            x, y, _, _ = box  # ✅ Only update position, keep w & h unchanged
            current_bbox[0] = int(x)
            current_bbox[1] = int(y)

            # print('keyyyyyyyyyyyyyyy',key)
            #
            # if key == ord('w') or key == ord('s'):
            #     print('qqqqq')
            #     x, y, _, h = box  # ✅ Only update position, keep w & h unchanged
            #     current_bbox[0] = int(x)
            #     current_bbox[1] = int(y)
            #     current_bbox[3] = int(h)
            #
            # elif key == ord('h') or key == ord('n'):
            #     x, y, w, _ = box  # ✅ Only update position, keep w & h unchanged
            #     current_bbox[0] = int(x)
            #     current_bbox[1] = int(y)
            #     current_bbox[2] = int(w)
            #
            # elif key == '114' or key == '108':
            #     _, y, w, h = box  # ✅ Only update position, keep w & h unchanged
            #     current_bbox[1] = int(y)
            #     current_bbox[2] = int(w)
            #     current_bbox[3] = int(h)
            #
            # elif key == '117' or key == '100':
            #     x, _, w, h = box  # ✅ Only update position, keep w & h unchanged
            #     current_bbox[0] = int(x)
            #     current_bbox[2] = int(w)
            #     current_bbox[3] = int(h)
            #
            # else:
            #     current_bbox = [int(v) for v in box]




    else:
            tracking = False


def draw_bounding_box():
    global current_bbox
    if current_bbox:
        x, y, w, h = current_bbox
        color = (0, 0, 255)  # Green box like a camera
        thickness = 2
        corner_length = 10  # Length of corner segments

        # Top-left corner
        cv2.line(frame, (x, y), (x + corner_length, y), color, thickness)
        cv2.line(frame, (x, y), (x, y + corner_length), color, thickness)

        # Top-right corner
        cv2.line(frame, (x + w, y), (x + w - corner_length, y), color, thickness)
        cv2.line(frame, (x + w, y), (x + w, y + corner_length), color, thickness)

        # Bottom-left corner
        cv2.line(frame, (x, y + h), (x + corner_length, y + h), color, thickness)
        cv2.line(frame, (x, y + h), (x, y + h - corner_length), color, thickness)

        # Bottom-right corner
        cv2.line(frame, (x + w, y + h), (x + w - corner_length, y + h), color, thickness)
        cv2.line(frame, (x + w, y + h), (x + w, y + h - corner_length), color, thickness)

        # Display coordinates
        text = f"X: {x}, Y: {y}, W: {w}, H: {h}"
        cv2.putText(frame, text, (x, y - 20), cv2.FONT_HERSHEY_SIMPLEX, 0.6, color, 2)

def handle_keyboard_events():
    global current_bbox,key
    if current_bbox:
        x, y, w, h = current_bbox

        step = resize_step  # Normal resizing step

        if key == ord('w'):  # Increase width
            w += step
        elif key == ord('s'):  # Decrease width
            # w = max(DEFAULT_WIDTH, w - step)
            w = w - step

        elif key == ord('h'):  # Increase height
            h += step
        elif key == ord('n'):  # Decrease height
            # h = max(DEFAULT_HEIGHT, h - step)
            h = h - step

        # elif key == ord('r'):  # Move RIGHT
        #     x += step
        # elif key == ord('l'):  # Move LEFT
        #     x -= step
        # elif key == ord('u'):  # Move UP
        #     y -= step
        # elif key == ord('d'):  # Move DOWN
        #     y += step


        # ✅ Correctly update `current_bbox`
        current_bbox = [x, y, w, h]

cv2.namedWindow("Frame")
cv2.setMouseCallback("Frame", mouse_events)

def get_object_and_screen_direction():
    """Finds the object's center and compares it to the screen center to determine direction. Also draws a line from the object's bounding box to the screen center and to a specific (x, y) coordinate if provided."""
    global current_bbox, frame
    if current_bbox is not None and frame is not None:
        x, y, w, h = current_bbox
        obj_center_x = x + w // 2
        obj_center_y = y + h // 2
        frame_h, frame_w = frame.shape[:2]
        screen_center_x = frame_w // 2
        screen_center_y = frame_h // 2

        # Draw centers for visualization
        cv2.circle(frame, (obj_center_x, obj_center_y), 5, (0, 255, 0), -1)
        cv2.circle(frame, (screen_center_x, screen_center_y), 5, (255, 0, 0), -1)
        cv2.line(frame, (obj_center_x, obj_center_y), (screen_center_x, screen_center_y), (0, 255, 255), 2)

        # Draw a line from the object's bounding box to a specific (x, y) coordinate
        # For demonstration, let's use the top-left corner of the bounding box (x, y)
        cv2.line(frame, (x, y), (screen_center_x, screen_center_y), (255, 0, 255), 2)
        # You can change (x, y) to any other point as needed

        # Display coordinates of object center and screen center
        cv2.putText(frame, f"Obj Center: ({obj_center_x}, {obj_center_y})", (20, 70), cv2.FONT_HERSHEY_SIMPLEX, 0.8, (0, 255, 0), 2)
        cv2.putText(frame, f"Screen Center: ({screen_center_x}, {screen_center_y})", (20, 100), cv2.FONT_HERSHEY_SIMPLEX, 0.8, (255, 0, 0), 2)

        # Determine direction
        dx = obj_center_x - screen_center_x
        dy = obj_center_y - screen_center_y
        direction = []
        if abs(dx) > 10:  # threshold for horizontal movement
            if dx > 0:
                direction.append('right')
            else:
                direction.append('left')
        if abs(dy) > 10:  # threshold for vertical movement
            if dy > 0:
                direction.append('down')
            else:
                direction.append('up')
        dir_str = ', '.join(direction) if direction else 'centered'
        cv2.putText(frame, f"Direction: {dir_str}", (20, 40), cv2.FONT_HERSHEY_SIMPLEX, 1, (0,0,255), 2)
        return dir_str
    return None

while cap.isOpened():
    ret, frame = cap.read()
    if not ret:
        break

    key = cv2.waitKey(1) & 0xFF

    yolo_detection()
    default_tracking()
    # draw_yolo_detections()
    handle_keyboard_events()
    draw_bounding_box()
    update_tracking()
    get_object_and_screen_direction()  # <-- Call the new function here

    cv2.imshow("Frame", frame)
    if key == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()
# ----------------------------

# -----------------------------------------------------------------------------------oop version---------
# import cv2
# import numpy as np
# from ultralytics import YOLO
#
# class BoundingBox:
#     """ Handles bounding box size, position, and user interaction """
#     def __init__(self, default_width=50, default_height=80, resize_step=10):
#         self.x = None
#         self.y = None
#         self.w = default_width
#         self.h = default_height
#         self.resize_step = resize_step
#         self.tracking = False
#         self.tracker = cv2.TrackerCSRT_create()
#
#     def set_initial_bbox(self, x, y):
#         """ Sets bounding box at the initial detected object """
#         self.x = x
#         self.y = y
#         self.tracking = False
#
#     def update_position(self, x, y):
#         """ Updates bounding box position while dragging """
#         if self.x is not None and self.y is not None:
#             self.x = x
#             self.y = y
#
#     def resize(self, action):
#         """ Resizes bounding box with smooth adjustments """
#         if action == "Increase Width":
#             self.w += self.resize_step
#         elif action == "Decrease Width":
#             self.w = max(20, self.w - self.resize_step)
#         elif action == "Increase Height":
#             self.h += self.resize_step
#         elif action == "Decrease Height":
#             self.h = max(20, self.h - self.resize_step)
#
#     def start_tracking(self, frame):
#         """ Initializes tracker with the bounding box """
#         if self.x is not None and self.y is not None:
#             self.tracker.init(frame, (self.x, self.y, self.w, self.h))
#             self.tracking = True
#
#     def update_tracking(self, frame):
#         """ Updates tracking position while following an object """
#         if self.tracking:
#             success, box = self.tracker.update(frame)
#             if success:
#                 self.x, self.y, self.w, self.h = [int(v) for v in box]
#             else:
#                 self.tracking = False
#
# class YOLOTracker:
#     """ Handles YOLO object detection and selects bounding box """
#     def __init__(self, model_path):
#         self.model = YOLO(model_path)
#         self.detections = []
#
#     def detect_objects(self, frame):
#         """ Runs YOLO detection and stores results """
#         results = self.model.predict(source=frame, save=False, show=False)
#         self.detections = results[0].boxes.data.cpu().numpy()
#
#     def get_first_detection(self):
#         """ Returns the coordinates of the first detected object """
#         if len(self.detections) > 0:
#             x1, y1, x2, y2, conf, cls = self.detections[0][:6]
#             return (int((x1 + x2) // 2), int((y1 + y2) // 2))
#         return None

# class VideoProcessor:
#     """ Manages frame processing, mouse events, and main execution loop """
#     def __init__(self, video_path, yolo_model_path):
#         self.cap = cv2.VideoCapture(video_path)
#         self.bbox = BoundingBox()
#         self.yolo_tracker = YOLOTracker(yolo_model_path)
#         cv2.namedWindow("Frame")
#         cv2.setMouseCallback("Frame", self.mouse_events)
#
#     def mouse_events(self, event, x, y, flags, param):
#         """ Handles mouse interactions for selecting and moving bounding box """
#         if event == cv2.EVENT_LBUTTONDOWN:
#             if self.bbox.x is None or self.bbox.y is None:
#                 self.bbox.set_initial_bbox(x, y)
#             else:
#                 self.bbox.update_position(x, y)
#
#         elif event == cv2.EVENT_MOUSEMOVE:
#             if self.bbox.x is not None and self.bbox.y is not None:
#                 self.bbox.update_position(x, y)
#
#         elif event == cv2.EVENT_LBUTTONUP:
#             if self.bbox.x is not None and self.bbox.y is not None:
#                 self.bbox.start_tracking(self.frame)
#
#     def handle_keyboard_events(self):
#         """ Adjusts bounding box size using keyboard inputs """
#         key = cv2.waitKey(1) & 0xFF
#
#         if key == ord('w'):  # Increase width
#             self.bbox.resize("Increase Width")
#         elif key == ord('s'):  # Decrease width
#             self.bbox.resize("Decrease Width")
#         elif key == ord('h'):  # Increase height
#             self.bbox.resize("Increase Height")
#         elif key == ord('n'):  # Decrease height
#             self.bbox.resize("Decrease Height")
#         elif key == ord('q'):  # Quit
#             return False
#         return True
#
#     def draw_elements(self):
#         """ Draws bounding box and YOLO detections on the frame """
#         for det in self.yolo_tracker.detections:
#             x1, y1, x2, y2, conf, cls = det[:6]
#             cv2.rectangle(self.frame, (int(x1), int(y1)), (int(x2), int(y2)), (255, 0, 0), 2)
#             cv2.putText(self.frame, f"{int(cls)}: {conf:.2f}", (int(x1), int(y1) - 10),
#                         cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 0, 0), 2)
#
#         if self.bbox.x is not None and self.bbox.y is not None:
#             cv2.rectangle(self.frame, (self.bbox.x, self.bbox.y),
#                           (self.bbox.x + self.bbox.w, self.bbox.y + self.bbox.h),
#                           (0, 255, 0), 2)
#             text = f"X: {self.bbox.x}, Y: {self.bbox.y}, W: {self.bbox.w}, H: {self.bbox.h}"
#             cv2.putText(self.frame, text, (self.bbox.x, self.bbox.y - 20),
#                         cv2.FONT_HERSHEY_SIMPLEX, 0.6, (0, 255, 0), 2)
#
#     def process_video(self):
#         """ Main execution loop for video processing """
#         while self.cap.isOpened():
#             ret, self.frame = self.cap.read()
#             if not ret:
#                 break
#
#             # Run YOLO detection
#             self.yolo_tracker.detect_objects(self.frame)
#
#             # Assign bounding box to first detected object
#             detection = self.yolo_tracker.get_first_detection()
#             if detection and not self.bbox.tracking:
#                 self.bbox.set_initial_bbox(*detection)
#                 self.bbox.start_tracking(self.frame)
#
#             # Update tracking
#             self.bbox.update_tracking(self.frame)
#
#             # Draw elements on the frame
#             self.draw_elements()
#
#             # Handle keyboard input
#             if not self.handle_keyboard_events():
#                 break
#
#             cv2.imshow("Frame", self.frame)
#
#         self.cap.release()
#         cv2.destroyAllWindows()
#
# # ✅ Run the tracker using OOP
# video_tracker = VideoProcessor("assets/football.mp4", "yolo11n.pt")
# video_tracker.process_video()
