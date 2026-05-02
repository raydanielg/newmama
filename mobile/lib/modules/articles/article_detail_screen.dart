import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:get/get.dart';

class ArticleDetailScreen extends StatelessWidget {
  const ArticleDetailScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final Map<String, dynamic> article = Get.arguments['article'];
    return Scaffold(
      backgroundColor: const Color(0xFFFDFBF7),
      body: CustomScrollView(
        slivers: [
          SliverAppBar(
            expandedHeight: 300.h,
            floating: false,
            pinned: true,
            backgroundColor: const Color(0xFFFDFBF7),
            leading: GestureDetector(
              onTap: () => Get.back(),
              child: Container(
                margin: EdgeInsets.all(8.w),
                decoration: BoxDecoration(
                  color: Colors.white.withOpacity(0.9),
                  borderRadius: BorderRadius.circular(10.r),
                ),
                child: Icon(
                  Icons.arrow_back,
                  color: const Color(0xFF1E293B),
                  size: 20.sp,
                ),
              ),
            ),
            flexibleSpace: FlexibleSpaceBar(
              background: Image.network(
                article['image'],
                fit: BoxFit.cover,
              ),
            ),
          ),
          SliverToBoxAdapter(
            child: Padding(
              padding: EdgeInsets.all(24.w),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Container(
                        padding: EdgeInsets.symmetric(horizontal: 12.w, vertical: 6.h),
                        decoration: BoxDecoration(
                          color: const Color(0xFFEEF2FF),
                          borderRadius: BorderRadius.circular(8.r),
                        ),
                        child: Text(
                          article['category'],
                          style: TextStyle(
                            fontSize: 12.sp,
                            fontWeight: FontWeight.w800,
                            color: const Color(0xFF1E40AF),
                          ),
                        ),
                      ),
                      SizedBox(width: 16.w),
                      Icon(
                        Icons.access_time,
                        color: const Color(0xFF94A3B8),
                        size: 16.sp,
                      ),
                      SizedBox(width: 4.w),
                      Text(
                        article['readTime'] + ' read',
                        style: TextStyle(
                          fontSize: 13.sp,
                          fontWeight: FontWeight.w500,
                          color: const Color(0xFF94A3B8),
                        ),
                      ),
                    ],
                  ),
                  SizedBox(height: 20.h),
                  Text(
                    article['title'],
                    style: TextStyle(
                      fontSize: 28.sp,
                      fontWeight: FontWeight.w900,
                      color: const Color(0xFF1E293B),
                      height: 1.2,
                      letterSpacing: -0.5,
                    ),
                  ),
                  SizedBox(height: 24.h),
                  Container(
                    padding: EdgeInsets.all(20.w),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(20.r),
                      border: Border.all(color: const Color(0xFFE2E8F0)),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        _buildSection(
                          'Introduction',
                          'Maintaining good health during pregnancy is essential for both mother and baby. This article provides comprehensive guidance on ${article['category'].toLowerCase()} to help you have a healthy pregnancy journey.',
                        ),
                        SizedBox(height: 24.h),
                        _buildSection(
                          'Key Points',
                          '• Regular prenatal checkups are crucial\n'
                          '• Balanced nutrition supports fetal development\n'
                          '• Moderate exercise improves overall health\n'
                          '• Stay hydrated throughout the day\n'
                          '• Get adequate rest and sleep',
                        ),
                        SizedBox(height: 24.h),
                        _buildSection(
                          'When to Contact Your Doctor',
                          'Always consult your healthcare provider if you experience unusual symptoms, severe pain, or have concerns about your pregnancy. Early detection of issues can prevent complications.',
                        ),
                        SizedBox(height: 24.h),
                        Container(
                          padding: EdgeInsets.all(16.w),
                          decoration: BoxDecoration(
                            color: const Color(0xFFFEF3C7),
                            borderRadius: BorderRadius.circular(12.r),
                          ),
                          child: Row(
                            children: [
                              Icon(
                                Icons.lightbulb,
                                color: const Color(0xFFD97706),
                                size: 24.sp,
                              ),
                              SizedBox(width: 12.w),
                              Expanded(
                                child: Text(
                                  'Tip: Keep a pregnancy journal to track your progress and note any questions for your next appointment.',
                                  style: TextStyle(
                                    fontSize: 13.sp,
                                    fontWeight: FontWeight.w600,
                                    color: const Color(0xFF92400E),
                                    height: 1.5,
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                  SizedBox(height: 32.h),
                  SizedBox(
                    width: double.infinity,
                    height: 56.h,
                    child: ElevatedButton.icon(
                      onPressed: () {},
                      icon: Icon(Icons.share, size: 20.sp),
                      label: Text(
                        'Share Article',
                        style: TextStyle(
                          fontSize: 16.sp,
                          fontWeight: FontWeight.w800,
                        ),
                      ),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF1E40AF),
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(16.r),
                        ),
                      ),
                    ),
                  ),
                  SizedBox(height: 24.h),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSection(String title, String content) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          title,
          style: TextStyle(
            fontSize: 20.sp,
            fontWeight: FontWeight.w800,
            color: const Color(0xFF1E293B),
          ),
        ),
        SizedBox(height: 12.h),
        Text(
          content,
          style: TextStyle(
            fontSize: 15.sp,
            fontWeight: FontWeight.w500,
            color: const Color(0xFF475569),
            height: 1.7,
          ),
        ),
      ],
    );
  }
}
