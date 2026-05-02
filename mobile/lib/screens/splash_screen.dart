import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:animate_do/animate_do.dart';
import 'package:get/get.dart';
import 'welcome_screen.dart';

class SplashScreen extends StatefulWidget {
  const SplashScreen({super.key});

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> with TickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _animation;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(
      duration: const Duration(seconds: 2),
      vsync: this,
    );
    _animation = CurvedAnimation(
      parent: _controller,
      curve: Curves.easeInOut,
    );
    _controller.forward();
    
    Future.delayed(const Duration(seconds: 3), () {
      Get.off(() => const WelcomeScreen(), transition: Transition.fadeIn);
    });
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFFDFBF7),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Pulse(
              duration: const Duration(seconds: 2),
              infinite: true,
              child: Container(
                width: 120.w,
                height: 120.h,
                decoration: BoxDecoration(
                  color: const Color(0xFFEEF2FF),
                  borderRadius: BorderRadius.circular(24.r),
                ),
                child: Center(
                  child: AnimatedBuilder(
                    animation: _animation,
                    builder: (context, child) {
                      return CustomPaint(
                        size: Size(80.w, 80.h),
                        painter: LogoPainter(_animation.value),
                      );
                    },
                  ),
                ),
              ),
            ),
            SizedBox(height: 32.h),
            FadeInUp(
              delay: const Duration(milliseconds: 500),
              child: Text(
                'Mamacare AI',
                style: TextStyle(
                  fontSize: 32.sp,
                  fontWeight: FontWeight.w900,
                  color: const Color(0xFF1E293B),
                  letterSpacing: -0.5,
                ),
              ),
            ),
            SizedBox(height: 8.h),
            FadeInUp(
              delay: const Duration(milliseconds: 700),
              child: Text(
                'Maternal Health Management',
                style: TextStyle(
                  fontSize: 14.sp,
                  fontWeight: FontWeight.w600,
                  color: const Color(0xFF64748B),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class LogoPainter extends CustomPainter {
  final double progress;

  LogoPainter(this.progress);

  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()
      ..color = const Color(0xFF1E40AF)
      ..strokeWidth = 3
      ..style = PaintingStyle.stroke
      ..strokeCap = StrokeCap.round;

    final dotPaint = Paint()
      ..color = const Color(0xFFD63384)
      ..style = PaintingStyle.fill;

    final path = Path();
    
    // Draw M shape
    path.moveTo(size.width * 0.15, size.height * 0.75);
    path.lineTo(size.width * 0.15, size.height * 0.25);
    path.lineTo(size.width * 0.35, size.height * 0.55);
    path.lineTo(size.width * 0.5, size.height * 0.4);
    path.lineTo(size.width * 0.65, size.height * 0.55);
    path.lineTo(size.width * 0.85, size.height * 0.25);
    path.lineTo(size.width * 0.85, size.height * 0.75);

    final pathMetrics = path.computeMetrics();
    for (var metric in pathMetrics) {
      final extractPath = metric.extractPath(0, metric.length * progress);
      canvas.drawPath(extractPath, paint);
    }

    // Draw dot (pulse effect)
    if (progress > 0.8) {
      final dotProgress = (progress - 0.8) / 0.2;
      canvas.drawCircle(
        Offset(size.width * 0.5, size.height * 0.2),
        4 * dotProgress,
        dotPaint,
      );
    }
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => true;
}
